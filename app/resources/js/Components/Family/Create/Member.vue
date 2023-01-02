<template>
    <VForm action="/member/create" class="w-full flex flex-col px-10 py-5"
           @success="save"
    >
        <template #default="{data, progress, errors}">
            <span class="h1 text-xxxl mt-auto text-center w-full">
                {{ isEdit ? $t('family.title.member') : $t('family.title.new_member') }}
            </span>

            <VInput v-model="member.name"
                    :errors="errors"
                    :title="$t('field.title.name')"
                    name="name"
                    class="mt-5"
            />

            <VOptions v-model="member.gender"
                      vertical
                      :payload="genderOptions"
                      class="mt-5"
                      name="gender"
                      :errors="errors"
            >
                <template #default="{option}">
                    <div class="option flex-col items-center justify-center"
                         :class="{
                        'option--selected': option.selected
                     }"
                    >
                        <i :class="`icon-${option.id} text-xxxl`" />
                        <span class="mt-1">{{ option.name }}</span>
                    </div>
                </template>
            </VOptions>

            <div class="flex flex-col my-5">
                <Switch v-model="member.invite"
                        name="invite"
                        :title="$t('family.title.invite_member')"
                />

                <VInput v-if="member.invite"
                        v-model="member.email"
                        name="email"
                        class="mt-5"
                        :errors="errors"
                />
            </div>

            <input type="hidden" name="validate_only" value="1" />

            <div class="flex items-center justify-around mt-5">
                <VButton basic
                         type="span"
                         @click="close"
                >
                    {{ $t('common.button.cancel') }}
                </VButton>

                <VButton v-if="isEdit"
                         type="span"
                         danger
                         bordered
                         @click="remove"
                >
                    {{ $t('common.button.delete') }}
                </VButton>

                <VButton primary
                         :progress="progress"
                >
                    {{ $t(isEdit ? 'common.button.save' : 'common.button.add') }}
                </VButton>
            </div>
        </template>
    </VForm>
</template>
<script>
import {computed, inject, ref} from 'vue';
import VOptions from '@/Elements/Options';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import Switch from '@/Elements/Switch';
import VForm from '@/Elements/Form';

export default {
    name: 'Member',
    components: {VForm, Switch, VButton, VInput, VOptions},
    props: {
        members: Array,
        index: Number,
    },
    emits: ['close'],
    setup(props, { emit }) {
        const app = inject('app');
        const member = ref(Object.assign({
            gender: null,
            status: null,
            name: null
        }, props.members[props.index] || {}));
        const isEdit = computed(() => {
            return typeof props.index !== 'undefined';
        });

        function close() {
            emit('close');
        }

        return {
            member,
            genderOptions: app.constant.asPayload('gender', 'member.title.gender.'),
            isEdit,
            close,
            remove() {
                props.members.splice(props.index, 1);
                close();
            },
            save() {
                if (!member.value.invite) {
                    member.value.email = null;
                }

                if (isEdit.value) {
                    props.members[props.index] = member.value;
                } else {
                    props.members.push(member.value);
                }
                close();
            },
        }
    }
}
</script>
