<template>
    <AdminLayout>
        <Head title="Administrative Menu"></Head>
            <section class="filter-container">

            </section>
            <section class="content-container">
                <div class="content-header">
                    <button class="primary button">
                        New Employee
                    </button>
                </div>
                <div class="content-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Departament</th>
                                <th>Total Access</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :class="{oddRow:(i%2)===0}" v-for="(user,i) in users">
                                <td class="col">{{ user.document }}</td>
                                <td class="col">{{ user.name }}</td>
                                <td class="col">{{ user.last_name }}</td>
                                <td class="col">{{ user.production_departament ? user.production_departament.name : 'Sin departamento' }}</td>
                                <td class="num-col col">{{ user.total_access! }}</td>
                                <td class="col">
                                    <div class="button-container">
                                        <button class="update button">
                                            Update
                                        </button>
                                        <AuthHistoryModal :employee="user"></AuthHistoryModal>
                                        <button class="danger button">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="content-footer">

                </div>
            </section>
    </AdminLayout>
</template>

<script setup lang="ts">
    import AdminLayout from '@/layout/AdminLayout.vue';
    import { IPaginate, IUser } from '@/types/models';
    import { Head } from '@inertiajs/vue3';
    import axios from 'axios';    
    import { computed, onMounted, ref } from 'vue';
    import AuthHistoryModal from '@/components/modals/user/AuthHistoryModal.vue';

    axios.defaults.withCredentials = true;

    const data_paginate = ref<IPaginate<IUser> | null>(null);
    const loading = ref(false);

    const page = ref(1);

    const users = computed(()=>{
        if (!Boolean(data_paginate)) return [];
        const {value} = data_paginate;
        if(!Boolean(value)) return [];
        const {data} = value!;
        return data;
    });

    function next_page(){
        page.value++;
    }

    function prev_page(){
        page.value--;
    }

    onMounted(async ()=>{
        loading.value = true;
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.get<IPaginate<IUser>>('/api/users',{withCredentials:true});        
        data_paginate.value = response.data;        
        loading.value = false;
    });
</script>

<style scoped>
    @tailwind components;
    @layer components{

        .content-container{
            @apply flex flex-col gap-2;
            @apply px-4 py-3;
        }

        .content-header{
            @apply flex justify-end;
        }

        .table {
            @apply w-full;
        }

        .table > thead {
            @apply bg-gray-400;
        }

        .table > tbody > tr {
            @apply bg-gray-200;
        }

        .oddRow{
            @apply bg-gray-100;
        }

        .col{
            @apply px-3 py-2;
        }

        .num-col{
            @apply text-end;
        }
        .button-container{
            @apply flex flex-wrap justify-end gap-2;
        }
    }
</style>