<template>
    <div>
      <Button :type="buttonEnum.PRIMARY" :click="openModal">
        <div class="button-content">
          <span>Subir archivo CSV</span>
        </div>
      </Button>
      <div v-if="visible" class="modal-container" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-content__head">
            <h2 class="content-head__title">Subir archivo CSV</h2>
          </div>
          <div class="modal-content__body">
            <form @submit.prevent="submit" enctype="multipart/form-data" class="form-container">
              <div class="input-container">
                <label class="label block mb-1" for="csvFile">
                  Selecciona un archivo CSV
                </label>
                <input
                    id="csvFile"
                    name="file"
                    class="input w-full"
                    type="file"
                    accept=".csv"
                    @change="handleFileUpload"
                    required
                    />
              </div>
              <div class="col-span-2 flex justify-end">
                <button
                  class="submit-buttom"
                  :disabled="!form.file"
                  type="submit"
                >
                  Subir
                </button>
              </div>
            </form>            
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import Button from '@/components/Button.vue';
  import { EButtonType } from '@/enums';
  
  const buttonEnum = EButtonType;
  const visible = ref(false);
  
  const form = useForm({
    file: null as File | null,
  });
  
  function openModal() {
    visible.value = true;
  }
  
  function closeModal() {
    visible.value = false;
    form.file = null;
  }
  
  function handleFileUpload(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
      form.file = target.files[0];
    }
  }
  
  function submit() {
    form.post('/admin/users/mass_create', {
      onSuccess: () => {
        alert('Archivo subido con éxito');
        closeModal();
      },
      onError: (errors) => {
        alert('Hubo un error al subir el archivo');
        console.error(errors);
      }
    });
  }
  </script>
  
  <style scoped>
  @tailwind components;
  @layer components{
      .modal-container{
          @apply fixed inset-0;
          @apply flex items-center justify-center;
          @apply bg-gray-900 bg-opacity-50;
          z-index: 10;
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
          /* hover */
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