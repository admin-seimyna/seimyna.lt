<template>
    <div class="w-full flex flex-col px-10 py-5">
        <span class="h1 text-xxxl mt-auto text-center w-full">
            {{ isEdit ? $t('family.title.member') : $t('family.title.new_member') }}
        </span>

        <VInput v-model="member.name"
                :title="$t('field.title.name')"
                class="mt-5"
        />

        <VOptions v-model="member.gender"
                  vertical
                  :payload="genderOptions"
                  class="mt-5"
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

        <VOptions v-if="member.gender"
                  v-model="member.status"
                  :payload="statuses"
                  class="mt-5"
        />

        <div class="flex items-center justify-around mt-5">
            <VButton basic
                     @click="close"
            >
                {{ $t('common.button.cancel') }}
            </VButton>

            <VButton v-if="isEdit"
                     danger
                     bordered
                     @click="remove"
            >
                {{ $t('common.button.delete') }}
            </VButton>

            <VButton primary
                     :disabled="addIsDisabled"
                     @click="save"
            >
                {{ $t(isEdit ? 'common.button.save' : 'common.button.add') }}
            </VButton>
        </div>
    </div>
</template>
<script>
import {computed, inject, ref} from 'vue';
import VOptions from '@/Elements/Options';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';

export default {
    name: 'Member',
    components: {VButton, VInput, VOptions},
    props: {
        members: Array,
        index: Object,
    },
    emits: ['close'],
    setup(props, { emit }) {
        const app = inject('app');
        const statuses = app.constant.get('member_status');
        const member = ref(Object.assign({
            gender: null,
            status: null,
            name: null
        }, props.members[props.index] || {}));
        const isEdit = computed(() => {
            return typeof props.index !== 'undefined';
        });

        function getStatusOrder(id) {
            switch (id) {
                case statuses.GRANDFATHER:
                case statuses.GRANDMOTHER:
                    return 0;
                case statuses.FATHER:
                case statuses.MOTHER:
                    return 1;
                case statuses.SON:
                case statuses.DAUGHTER:
                    return 2;
            }
        }

        function close() {
            emit('close');
        }

        return {
            member,
            genderOptions: app.constant.asPayload('gender', 'member.title.gender.'),
            isEdit,
            statuses: computed(() => {
                const gender = app.constant.get('gender');

                return app.constant.asPayload('member_status', 'member.title.status.').filter((status) => {
                    if (member.value.gender === gender.MALE) {
                        return [statuses.FATHER, statuses.SON, statuses.GRANDFATHER].indexOf(status.id) !== -1;
                    }
                    return [statuses.MOTHER, statuses.DAUGHTER, statuses.GRANDMOTHER].indexOf(status.id) !== -1;
                }).sort((a, b) => {
                    return getStatusOrder(a.id) - getStatusOrder(b.id);
                });
            }),
            addIsDisabled: computed(() => {
                for (const key of ['gender', 'status', 'name']) {
                    if (!member.value[key]) {
                        return true;
                    }
                }

                return false;
            }),
            close,
            remove() {
                props.members.splice(props.index, 1);
                close();
            },
            save() {
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
