<template>
    <Button v-if="employee" :type="buttonEnum.SECONDARY" :click="openModal">
        <div class="button-content">
            <span >Update</span>
        </div>
    </Button>
    <Button v-else :type="buttonEnum.PRIMARY" :click="openModal">
        <div class="button-content">
            <span >New employee</span>
        </div>
    </Button>
    <div v-if="visible" class="modal-container" @click.self="closeModal">
        <span v-if="loading">Loading ...</span>
        <div v-else class="modal-content">
            <div class="modal-content__head">
                <h2 class="content-head__title">{{ employee ? 'Update employee' : 'New employee' }}</h2>
            </div>
            <div class="modal-content__body">
                <form @submit.prevent="submit" class="form-container grid grid-cols-2 gap-4">

                    <div class="input-container">
                        <label class="label block mb-1" for="name">Name</label>
                        <input
                        id="name"
                        class="input w-full"
                        v-model="form.name"
                        type="text"
                        placeholder="Name"
                        required
                        >
                    </div>

                    <div class="input-container">
                        <label class="label block mb-1" for="last_name">Last Name</label>
                        <input
                        id="last_name"
                        class="input w-full"
                        v-model="form.last_name"
                        type="text"
                        placeholder="Last Name"
                        required
                        >
                    </div>

                    <div class="input-container">
                        <label class="label block mb-1" for="document">Identity Number</label>
                        <input
                        id="document"
                        class="input w-full"
                        v-model="form.document"
                        type="number"
                        placeholder="Document"
                        required
                        >
                    </div>

                    <div class="input-container">
                        <label class="label block mb-1" for="email">Email</label>
                        <input
                        id="email"
                        class="input w-full"
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        >
                    </div>

                    <div class="input-container">
                        <label class="label block mb-1" for="production_departament_id">Department</label>
                        <select
                        id="production_departament_id"
                        class="input w-full"
                        v-model="form.production_departament_id"
                        required
                        >
                        <option :value="null">Select a department</option>
                        <option
                            v-for="dep in production_departaments"
                            :key="dep.id"
                            :value="dep.id"
                        >
                            {{ dep.name }}
                        </option>
                        </select>
                    </div>

                    <div class="checkbox-container">
                        <label class="label" for="remember"><span>Make admin</span></label>
                        <input :tabindex="3" id="remember" v-model="form.make_admin" class="input" type="checkbox">
                    </div>

                    <div class="col-span-2 flex justify-end">
                        <button
                        :class="{ 'disabled': (!form.isDirty && form.hasErrors) }"
                        class="submit-buttom"
                        type="submit"
                        >
                        {{ employee ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>            
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import Button from '@/components/Button.vue';
    import { EButtonType } from '@/enums';
    import { IProductionDepartament, IUser } from '@/types/models';
    import { InertiaForm, useForm } from '@inertiajs/vue3';
    import axios from 'axios';
    import { ref, watch } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import 'primeicons/primeicons.css'

    const props = defineProps<{
        employee?:IUser
    }>();

    let form:InertiaForm<{
        email:string|null;
        document:string|null;
        name:string|null;
        last_name:string|null;
        production_departament_id:number|null;
        make_admin:boolean;
    }> = configForm();

    const buttonEnum = EButtonType;

    const loading = ref(false);
    const visible = ref(false);
    const production_departaments = ref<IProductionDepartament[]>([]);

    function openModal(){
        visible.value = true;
    }

    function closeModal(){
        visible.value = false;
    }

    function submit(){
        console.log(form);
        
        if(props.employee){
            form.put(`/admin/users/${props.employee.id}`, {
                onSuccess: () => {
                    Inertia.visit(route('web.users.index'));
                }
            });
        }else{
            form.post('/admin/users', {
                onSuccess: () => {
                    Inertia.visit(route('web.users.index'));
                }
            });
        }
    }

    function configForm(){
        if(props.employee){
            return useForm({
                email:props.employee.email,
                document:props.employee.document,
                name:props.employee.name,
                last_name:props.employee.last_name,
                production_departament_id:props.employee.production_departament_id ?? null,
                make_admin:props.employee.is_admin
            });
        }else{
            return useForm({
                email:null,
                document:null,
                name:null,
                last_name:null,
                production_departament_id:null,
                make_admin:false
            });
        }
    }

    watch(
        visible,
        async (newVal)=>{
            if(!newVal) return;
            loading.value = true;
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.get<IProductionDepartament[]>(`/api/production-departaments`);
            production_departaments.value = response.data;
            loading.value = false;
        }
    );
</script>

<style scoped>
    @tailwind components;
    @layer components{
        .modal-container{
            @apply fixed inset-0;
            @apply flex items-center justify-center;
            @apply bg-gray-900 bg-opacity-50;
        }

        .modal-content{
            @apply bg-white p-4;
            @apply shadow-lg w-fit h-fit;
            @apply rounded-sm;
        }

        .modal-content__head{
            @apply border-b border-slate-600;
        }

        .content-head__title{
            @apply text-lg text-center;
        }

        .modal-content__body{
            @apply p-2;
        }

        .table > thead {
            @apply bg-slate-400;
        }

        .table > tbody > tr > td,
        .table > thead > tr > th {
            @apply px-2 py-1;
        }

        .oddRow{
            @apply bg-slate-200;
        }

        .success{
            @apply text-green-600;
        }

        .failed{
            @apply text-red-600;
        }

        .unidentified{
            @apply text-yellow-600;
        }

        .modal-content__footer{
            @apply px-3 py-2 flex justify-center;
        }

        .button-content{
            @apply flex gap-2 justify-center items-center;
        }

        .title-container{
            @apply flex flex-col mb-2;
        }
        .form-title {
            @apply text-3xl font-bold text-gray-600;
        }
        .form-description{
            @apply text-xs text-gray-400;
        }
        .status-container{
            @apply mb-4 text-center text-sm font-medium text-green-600;
        }
        .form-container{
            @apply flex flex-col gap-1;
        }
        .input-container {
            @apply flex flex-col gap-1;
        }
        .checkbox-container{
            @apply text-sm text-gray-400;
            @apply flex gap-1 mx-auto items-center;
        }
        .label{
            @apply flex justify-center items-center;
        }
        .submit-buttom{
            @apply px-2 py-1;
            @apply rounded-sm;
            @apply text-white bg-black;
            /*hover*/
            @apply hover:bg-gray-800;
            /* click */
            @apply hover:bg-gray-700;
        }
        .submit-buttom.disabled{
            @apply opacity-50;
        }
        .input{
            @apply rounded-sm border border-gray-300;
            @apply p-1;
            /* focus */
            @apply focus:border-4;
        }
        .input[type='number']::-webkit-inner-spin-button,
        .input[type='number']::-webkit-outer-spin-button{
            @apply hidden;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
        
    }
</style>