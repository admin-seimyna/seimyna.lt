<template>
    <div class="w-full h-full flex-center">
        <VSpinner v-if="loading"
                  class="w-16 h-16"
        />

        <div v-else-if="!requisition.activated_at"
             class="flex flex-col w-full"
        >
            <div class="flex flex-col px-10">
                <p class="mb-8 text-center w-full">
                    {{ $t('bank_account.message.nordigen_import_error') }}
                </p>

                <div class="flex-center">
                    <VButton basic
                             shadow
                             primary
                             class="mr-2"
                             @click="openBrowser"
                    >
                        {{ $t('common.button.try_again') }}
                    </VButton>
                    <VButton basic
                             shadow
                             @click="emit('back')"
                    >
                        {{ $t('bank_account.button.choose_other_bank') }}
                    </VButton>
                </div>
            </div>
        </div>

        <VPage v-else
               bubble-top-center
               class="px-10"
        >
            <VForm action="/finances/account/create"
                   @success="emit('next')"
                   class="flex flex-col w-full h-full"
            >
                <template #default="{data,errors,progress}">
                    <span class="h1 text-xxxl mt-8 mb-5 text-white">
                        {{ $t('bank_account.title.accounts') }}
                    </span>

                    <ul class="flex flex-col w-full h-full overflow-hidden">
                        <li v-for="(account, index) in accounts"
                            :key="account.uid"
                            class="flex border shadow p-5 rounded mb-2 bg-white"
                        >
                            <div class="w-14 pr-2">
                                <img :src="bank.logo"
                                     class="w-14 h-auto"
                                />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-primary-500 font-semibold">
                                    {{ bank.name }}
                                </span>

                                <span class="mt-2">
                                    {{ account.iban }}
                                </span>

                                <input type="hidden"
                                       :value="requisition.id"
                                       :name="`accounts[${index}][requisition_id]`"
                                />

                                <input type="hidden"
                                       :value="bank.id"
                                       :name="`accounts[${index}][bank_id]`"
                                />

                                <input type="hidden"
                                       :value="account.iban"
                                       :name="`accounts[${index}][iban]`"
                                />

                                <input type="hidden"
                                       :value="account.uid"
                                       :name="`accounts[${index}][uid]`"
                                />
                                <VInput v-model="account.name"
                                        class="mt-2"
                                        :title="$t('field.title.title')"
                                        :name="`accounts[${index}][name]`"
                                        :errors="errors"
                                />

                                <div v-if="!progress"
                                     class="flex items-center mt-3"
                                >
                                    <VButton basic
                                             type="span"
                                             shadow
                                             danger
                                             @click="removeAccount(index)"
                                    >
                                        {{ $t('common.button.cancel') }}
                                    </VButton>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="flex-center mt-auto py-5">
                        <VButton rounded
                                 shadow
                                 :progress="progress"
                                 class="w-16 h-16 bg-white"
                        >
                            <i class="icon-arr-down text-xl text-primary-500" />
                        </VButton>
                    </div>
                </template>
            </VForm>
        </VPage>
    </div>
</template>
<script>
import VSpinner from '@/Elements/Spinner';
import {inject, reactive, ref} from 'vue';
import axios from 'axios';
import moment from 'moment';
import VButton from '@/Elements/Button';
import VInput from '@/Elements/Input';
import VPage from '@/Components/Layout/Page';
import VForm from '@/Elements/Form';

export default {
    name: 'NordigenIframe',
    components: {VForm, VPage, VInput, VButton, VSpinner},
    props: {
        bank: Object,
    },
    emits: ['back', 'next'],
    setup(props, { emit }) {
        const app = inject('app');
        const loading = ref(true);
        const iframeRef = ref(null);
        const requisition = reactive({});
        const accounts = reactive([]);

        function openBrowser() {
            loading.value = true;
            app.browser.open(requisition.link, {
                location: true
            }).then((browser) => {
                    browser.show();
                    browser.addEventListener('exit', () => {
                        if (requisition.activated_at) {
                            loadAccounts();
                            return;
                        }
                        loading.value = false;
                    });
                    browser.addEventListener('loadstart', (event) => {
                        const url = event.url.split('?');
                        if (url[0] === requisition.redirect) {
                            if (url[1] && !url[1].match(/(error=).*/)) {
                                browser.hide();
                                browser.addEventListener('loadstop', () => {
                                    requisition.activated_at = moment();
                                    browser.close();
                                });
                            } else {
                                browser.close();
                            }
                        }
                    });
                });
        }

        function loadAccounts() {
            loading.value = true;
            axios.get(`/finances/nordigen/${requisition.id}/accounts`).then((response) => {
                response.data.forEach(account => accounts.push(account));
                loading.value = false;
            });
        }

        axios.post(`/finances/nordigen/${props.bank.id}`).then((response) => {
            Object.assign(requisition, response.data);
            if (!requisition.activated_at) {
                openBrowser();
            } else {
                loadAccounts();
            }
        });

        return {
            emit,
            requisition,
            loading,
            iframeRef,
            accounts,
            openBrowser,
            removeAccount(index) {
                app.dialog.defaultConfirm().then(() => {
                    accounts.splice(index, 1);
                });
            }
        }
    }
}
</script>
