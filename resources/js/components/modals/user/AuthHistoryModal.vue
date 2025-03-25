<template>
    <Button :type="button_type" :click="openModal">
        <div class="button-content">
            <span>History</span>
        </div>
    </Button>
    <div v-if="visible" class="modal-container" @click.self="closeModal">
        <span v-if="loading">Loading ...</span>
        <div v-else class="modal-content">
            <div class="modal-content__head">
                <h2 class="content-head__title">Employee Latest Logins - {{ employee.name }} {{ employee.last_name }}</h2>
            </div>
            <div class="modal-content__body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ip</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :class="{oddRow:(i%2)!==0}" v-for="(history,i) in histories">
                            <td>
                                {{ new Date(history.created_at).toLocaleString() }}
                            </td>
                            <td>
                                {{ history.ip }}
                            </td>
                            <td>
                                <span :class="{
                                    success:history.status == loginEnum.SUCCESS,
                                    failed:history.status===loginEnum.FAILED,
                                    unidentified:history.status===loginEnum.UNIDENTIFIED
                                }" >{{ history.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-content__footer">
                <Button :type="buttonEnum.SECONDARY" :click="downloadHistory">
                    <div class="button-content">
                        <i class="pi pi-file-export"></i>
                        <span>Download all history</span>
                    </div>
                </Button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import Button from '@/components/Button.vue';
    import { EButtonType, ELoginStatus } from '@/enums';
    import { ILoginHistory, IUser } from '@/types/models';
    import axios from 'axios';
    import { ref, watch } from 'vue';
    import 'primeicons/primeicons.css'

    const props = defineProps<{
        employee:IUser
    }>()

    const loginEnum = ELoginStatus;
    const buttonEnum = EButtonType;

    const button_type = ref<EButtonType>(EButtonType.INFO);
    const loading = ref(false);
    const visible = ref(false);
    const histories = ref<ILoginHistory[]>([]);

    function openModal(){
        visible.value = true;
    }

    function closeModal(){
        visible.value = false;
    }

    async function downloadHistory(){
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.get(`/api/users/${props.employee.id}/download/latest_logins`, {
            responseType: 'blob' // importante para obtener un blob
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
        const link = document.createElement('a');
        link.href = url;
        const today = new Date().toISOString().split('T')[0];
        link.setAttribute('download', `login-history-${today}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    watch(
        visible,
        async (newVal)=>{
            if(!newVal) return;
            loading.value = true;
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.get<ILoginHistory[]>(`/api/users/${props.employee.id}/latest_logins`);
            histories.value = response.data;
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
        
    }
</style>