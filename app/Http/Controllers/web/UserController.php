<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/employees/Index');
    }

    public function mass_store(Request $request)
    {
        // Validamos que se envíe un archivo CSV
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        // Obtenemos el archivo enviado y lo almacenamos temporalmente
        $archivo = $request->file('file');
        $filename = 'import_' . time() . '.' . $archivo->getClientOriginalExtension();
        $rutaArchivo = $archivo->storeAs('imports', $filename);
        $fullPath = Storage::path($rutaArchivo);

        try {
            // Ejecutar en una transacción para asegurar la misma conexión
            DB::transaction(function () use ($fullPath) {
                // 1. Eliminar la tabla temporal si existe y crearla con colación uniforme
                DB::unprepared("DROP TEMPORARY TABLE IF EXISTS temp_import");
                DB::unprepared("
                    CREATE TEMPORARY TABLE temp_import (
                        DOCUMENT VARCHAR(255) COLLATE utf8mb4_unicode_ci,
                        EMPLOYEE_NAME VARCHAR(255) COLLATE utf8mb4_unicode_ci,
                        EMPLOYEE_LAST_NAME VARCHAR(255) COLLATE utf8mb4_unicode_ci,
                        EMPLOYEE_EMAIL VARCHAR(255) COLLATE utf8mb4_unicode_ci,
                        PRODUCTION_DEPARTAMENT VARCHAR(255) COLLATE utf8mb4_unicode_ci
                    ) COLLATE=utf8mb4_unicode_ci;
                ");

                // 2. Ejecutar LOAD DATA LOCAL INFILE sin utilizar prepared statements
                $loadQuery = "
                    LOAD DATA LOCAL INFILE '" . addslashes($fullPath) . "'
                    INTO TABLE temp_import
                    FIELDS TERMINATED BY ',' 
                    ENCLOSED BY '\"'
                    LINES TERMINATED BY '\\n'
                    IGNORE 1 LINES
                ";
                DB::unprepared($loadQuery);

                // 3. Llamar al stored procedure que procesa los datos de la tabla temporal
                DB::statement("CALL sp_importar_datos();");
            });

            return redirect()->route('web.users.index')->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            // En caso de error, retornamos una redirección con mensaje de error (respuesta válida para Inertia)
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function store(UserRequest $request){

        $data = $request->validated();
        
        $data['password'] = Hash::make($data['document']);

        $user = User::create($data);

        if($data['make_admin']){
            $user->assignRole('admin_room_911');
        }

        return redirect()->route('web.users.index');

    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($data['document'] !== $user->document) {
            $data['password'] = Hash::make($data['document']);
        }
    
        $user->update($data);
    
        if (isset($data['make_admin']) && $data['make_admin']) {
            if (!$user->hasRole('admin_room_911')) {
                $user->assignRole('admin_room_911');
            }
        } else {
            if ($user->hasRole('admin_room_911')) {
                $user->removeRole('admin_room_911');
            }
        }
    
        return redirect()->route('web.users.index');
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('web.users.index');
    }
    
}
