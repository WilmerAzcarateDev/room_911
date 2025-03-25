import { ELoginStatus } from "@/enums";

export interface IUser {
    id:number;
    email:string;
    name:string;
    document:string;
    last_name:string;
    login_histories?:ILoginHistory[];
    total_access?:number;
    production_departament?:IProductionDepartament;
}

export interface ILoginHistory {
    user_id:number;
    status:ELoginStatus;
    created_at:Date;
    ip:string;
    user?:IUser;
}

export interface IPaginate<T>{
    data:T[];
    current_page:number;
    from:number;
    last_page:number;
    per_page:number;
    to:number;
    total:number;
}

export interface IProductionDepartament{
    id:number;
    name:string;
    users?:IUser[];
}