<template>
    <AdminLayout>
      <Head title="Administrative Menu" />
  
      <section class="filter-container">
     
          <div class="filter-group">
            <label class="" for="searchDocument">Search by employee ID</label>
            <input
              id="searchDocument"
              type="text"
              placeholder="e.g. 12345"
              v-model="searchDocument"
              class="input w-40"
            />
          </div>
  
          <div class="filter-group">
            <label class="block text-sm mb-1" for="department">Filter by department</label>
            <select
              id="department"
              class="input w-40"
              v-model="department"
            >
              <option value="">All departments</option>
              <option v-for="dep in allDepartments" :key="dep.id" :value="dep.id">
                {{ dep.name }}
              </option>
            </select>
          </div>
  
          <div class="filter-group">
            <label class="block text-sm mb-1" for="start">Initial access date</label>
            <input
              id="start"
              type="date"
              class="input w-40"
              v-model="start"
            />
          </div>
  
          <div class="filter-group">
            <label class="block text-sm mb-1" for="end">Final access date</label>
            <input
              id="end"
              type="date"
              class="input w-40"
              v-model="end"
            />
          </div>
  
          <div class="filter-group justify-end">
            <Button :click="clearFilter" :type="buttonEnum.SECONDARY">
                Clear filter
            </Button>
            <Button :click="filter" :type="buttonEnum.SECONDARY">
                 Filter
            </Button>
          </div>
  
      </section>
  
      <section class="content-container">
        <div class="content-head">
            <UserForm />
            <UserMassCreate></UserMassCreate>
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
              <tr
                v-for="(user, i) in users"
                :key="user.id"
                :class="{ oddRow: (i % 2) === 0 }"
              >
                <td class="col">{{ user.document }}</td>
                <td class="col">{{ user.name }}</td>
                <td class="col">{{ user.last_name }}</td>
                <td class="col">
                  {{ user.production_departament ? user.production_departament.name : 'Sin departamento' }}
                </td>
                <td class="num-col col">{{ user.total_access }}</td>
                <td class="col">
                  <div class="button-container">
                    <UserForm :employee="user" />
                    <AuthHistoryModal :employee="user" />
                    <UserDelete :employee="user" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div class="content-footer flex justify-center items-center gap-4 mt-4">
          <button
            @click="prevPage"
            :disabled="page <= 1"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded disabled:opacity-50"
          >
            Previous
          </button>
          <span class="text-sm">
            Page {{ page }} of {{ data_paginate?.last_page || 1 }}
          </span>
          <button
            @click="nextPage"
            :disabled="!!data_paginate && page >= data_paginate.last_page"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded disabled:opacity-50"
          >
            Next
          </button>
        </div>
      </section>
    </AdminLayout>
  </template>
  
  <script setup lang="ts">
  import AdminLayout from '@/layout/AdminLayout.vue';
  import { IPaginate, IProductionDepartament, IUser } from '@/types/models';
  import { Head } from '@inertiajs/vue3';
  import axios from 'axios';
  import { computed, onMounted, ref } from 'vue';
  
  import AuthHistoryModal from '@/components/modals/user/AuthHistoryModal.vue';
  import UserForm from '@/components/modals/user/UserForm.vue';
  import UserDelete from '@/components/modals/user/UserDelete.vue';
  import Button from '@/components/Button.vue';
  import UserMassCreate from '@/components/modals/user/UserMassCreate.vue';
  import { EButtonType } from '@/enums';
  
  axios.defaults.withCredentials = true;

  const buttonEnum = EButtonType;
  
  const data_paginate = ref<IPaginate<IUser> | null>(null);
  const loading = ref(false);
  const page = ref(1);
  
  const searchDocument = ref<string>('');
  const department = ref<string|number>('');
  const start = ref<string>('');
  const end = ref<string>('');
  
  const allDepartments = ref<IProductionDepartament[]>([]);
  
  const users = computed(() => {
    if (!data_paginate.value) return [];
    return data_paginate.value.data;
  });
  
  async function fetchUsers() {
    loading.value = true;
    try {
      const response = await axios.get<IPaginate<IUser>>('/api/users', {
        params: {
          page: page.value,
          start: start.value || undefined,
          end: end.value || undefined,
          production_departament_id: department.value || undefined,
          document: searchDocument.value || undefined
        },
        withCredentials: true
      });
      data_paginate.value = response.data;
    } finally {
      loading.value = false;
    }
  }

  async function fetchDepartaments(){
    const response = await axios.get<IProductionDepartament[]>(`/api/production-departaments`);
    allDepartments.value = response.data;
  }
  
  function nextPage(){
    if (data_paginate.value && page.value < data_paginate.value.last_page) {
      page.value++;
      fetchUsers();
    }
  }
  
  function prevPage(){
    if (page.value > 1) {
      page.value--;
      fetchUsers();
    }
  }
  
  function filter() {
    page.value = 1;
    fetchUsers();
  }
  
  function clearFilter() {
    searchDocument.value = '';
    department.value = '';
    start.value = '';
    end.value = '';
    page.value = 1;
    fetchUsers();
  }
  
  onMounted(async () => {
    await axios.get('/sanctum/csrf-cookie');
    fetchUsers();
    fetchDepartaments();
  });
  </script>
  
  <style scoped>
  @tailwind components;
  @layer components{
    .content-container{
      @apply flex flex-col gap-2 px-4 py-3;
    }
    .content-head{
      @apply flex flex-wrap items-center gap-2 justify-end;
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
    /* Estilos para los filtros */
    .filter-container{
        @apply border-b border-slate-800;
        @apply flex items-center;
    }

    .filter-group{
        @apply flex flex-wrap gap-1;
    }
    .input {
      @apply border border-gray-300 rounded p-1 focus:border-4;
    }
  }
  </style>  