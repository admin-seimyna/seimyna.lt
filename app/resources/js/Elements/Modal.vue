<template>
    <teleport to="body">
        <transition v-for="(modal, index) in modals"
                    :key="`${modal.id}-${index}`"
                    appear
                    name="modal"
        >
            <div v-if="modal.active"
                 ref="modalRef"
                 class="modal"
                 :class="[modal.zIndex,`modal--${modal.options.type}`]"
                 :style="{
                     'transition-duration': transitionDuration
                 }"
                 @click="closeOnOutside($event, index)"
            >
                <div class="modal-content">
                    <component :is="modal.component"
                               ref="componentRef"
                               v-bind="modal.props"
                               @close="close(index)"
                    />
                </div>
            </div>
        </transition>
    </teleport>
</template>
<script>
import {computed, inject, reactive, ref} from 'vue';

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
                const id = Math.random().toString(16).slice(2);
                modal.id = id;
                modal.zIndex = 1000 + modals.length;
                modal.active = true;
                modal.options = Object.assign({
                    type: 'vertical',
                    disableBackButton: false
                },modal.options || {})
                modals.push(modal);

                if (modal.options.disableBackButton) {
                    app.back.disable();
                } else {
                    app.back.on(`modal-${id}`, () => {
                        close(modals.length - 1);
                    });
                }

                setTimeout(() => {
                    const index = modals.findIndex(component => component.id === id);
                    resolve(componentRef.value[index]);
                }, props.transition);
            });
        }

        function close(index) {
            const modal = modals[index];
            if (!modal) {
                return;
            }
            modal.active = false;
            if (modal.options.disableBackButton) {
                app.back.enable();
            } else {
                app.back.off(`modal-${modal.id}`);
            }
            setTimeout(() => {
                if (typeof modal.onClose === 'function') {
                    modal.onClose();
                }
                modals.splice(index, 1);
            }, props.transition);
        }

        app.register('modal', open).then((options) => {
            // do something if you want
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
                if (modals[index].options.type === 'popup' && modalRef.value[index] === e.target) {
                    close(index);
                }
            }
        }
    }
}
</script>
