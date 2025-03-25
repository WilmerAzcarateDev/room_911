<template>
    <Button v-if="employee" :type="buttonEnum.DANGER" :click="openDeleteModal">
    <div class="button-content">
        <span>Delete</span>
    </div>
    </Button>
  
    <div v-if="deleteVisible" class="modal-container" @click.self="closeDeleteModal">
      <div class="modal-content">
        <div class="modal-content__head">
          <h2 class="content-head__title">Confirm Deletion</h2>
        </div>
        <div class="modal-content__body">
          <p>
            Are you sure you want to delete user 
            <strong>{{ employee.name }} {{ employee.last_name }}</strong>?
          </p>
        </div>
        <div class="modal-content__footer">
          <button @click="closeDeleteModal" class="cancel-button">Cancel</button>
          <button @click="deleteEmployee" class="delete-button">Delete</button>
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
  import 'primeicons/primeicons.css';
  
  const props = defineProps<{ employee: IUser }>();
  
    const buttonEnum = EButtonType;
  
  const loading = ref(false);
  const visible = ref(false);
  const deleteVisible = ref(false);
  
  
  function openDeleteModal(){
    deleteVisible.value = true;
  }
  
  function closeDeleteModal(){
    deleteVisible.value = false;
  }
  
  function deleteEmployee(){
    Inertia.delete(route('web.users.destroy', { user: props.employee.id }), {
        onSuccess: () => Inertia.visit(route('web.users.index')),
        onError: (errors) => console.error(errors),
    });
  }
  
  </script>
  
  <style scoped>
  @tailwind components;
  @layer components{
    .modal-container{
      @apply fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50;
    }
    .modal-content{
      @apply bg-white p-4 shadow-lg w-fit h-fit rounded-sm;
    }
    .modal-content__head{
      @apply border-b border-slate-600 mb-2;
    }
    .content-head__title{
      @apply text-lg text-center;
    }
    .modal-content__body{
      @apply p-2;
    }
    .modal-content__footer{
      @apply px-3 py-2 flex justify-center gap-4;
    }
    .cancel-button{
      @apply px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-sm;
    }
    .delete-button{
      @apply px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 text-sm;
    }
    .button-content{
      @apply flex gap-2 justify-center items-center;
    }
    .form-container{
      @apply flex flex-col gap-1;
    }
    .input-container{
      @apply flex flex-col gap-1;
    }
    .checkbox-container{
      @apply text-sm text-gray-400 flex gap-1 mx-auto items-center;
    }
    .label{
      @apply flex justify-center items-center;
    }
    .submit-buttom{
      @apply px-2 py-1 rounded-sm text-white bg-black hover:bg-gray-800 hover:bg-gray-700;
    }
    .submit-buttom.disabled{
      @apply opacity-50;
    }
    .input{
      @apply rounded-sm border border-gray-300 p-1 focus:border-4;
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
  