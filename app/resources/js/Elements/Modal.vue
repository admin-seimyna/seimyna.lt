<template>
    <teleport to="body">
        <transition v-for="(modal, index) in modals"
                    :key="`${modal.value.id}-${index}`"
                    appear
                    name="modal"
        >
            <div v-if="modal.value.active"
                 ref="modalRef"
                 class="modal"
                 :class="[modal.value.zIndex,`modal--${modal.value.options.type}`]"
                 :style="{
                     'transition-duration': transitionDuration
                 }"
                 @click="closeOnOutside($event, index)"
            >
                <div class="modal-content">
                    <component :is="modal.value.component"
                               ref="componentRef"
                               v-bind="modal.value.props"
                               @close="close(index)"
                    />
                </div>
            </div>
        </transition>
    </teleport>
</template>
<script>
import {computed, inject, onBeforeUnmount, reactive, ref} from 'vue';

export default {
    name: 'Modal',
    props: {
        transition: {
            type: Number,
            default: 250
        }
    },
    setup(props) {
        const app = inject('app');
        const modals = reactive([]);
        const componentRef = ref(null);
        const modalRef = ref(null);

        function open(modal) {
            return new Promise((resolve, reject) => {
                modal = ref(modal);
                const id = Math.random().toString(16).slice(2);
                modal.value.id = id;
                modal.value.zIndex = 1000 + modals.length;
                modal.value.active = true;
                modal.value.options = Object.assign({
                    type: 'vertical',
                    disableBackButton: false
                },modal.value.options || {})
                modals.push(modal);

                if (modal.value.options.disableBackButton) {
                    app.back.disable();
                } else {
                    app.back.on(`modal-${id}`, () => {
                        close(modals.length - 1);
                    });
                }

                setTimeout(() => {
                    const index = modals.findIndex(modal => modal.value.id === id);
                    resolve(componentRef.value[index]);
                }, props.transition);
            });
        }

        function close(index) {
            const modal = modals[index];
            if (!modal) {
                return;
            }
            modals[index].value.active = false;
            if (modal.value.options.disableBackButton) {
                app.back.enable();
            } else {
                app.back.off(`modal-${modal.value.id}`);
            }

            setTimeout(() => {
                if (typeof modal.value.onClose === 'function') {
                    modal.value.onClose();
                }
                modals.splice(index, 1);
            }, props.transition);
        }

        app.register('modal', open).then((options) => {
            // do something if you want
        });

        app.bus.on('modal:closeAll', () => {
            modals.forEach((m, index) => close(index));
        });

        onBeforeUnmount(() => {
            app.bus.off('modal:closeAll');
        });

        return {
            componentRef,
            modalRef,
            modals,
            close,
            transitionDuration: computed(() => {
                return `${props.transition / 1000}s`;
            }),

            closeOnOutside(e, index) {
                if (modals[index].value.options.type === 'popup' && modalRef.value[index] === e.target) {
                    close(index);
                }
            }
        }
    }
}
</script>
