<template>
    <teleport to="body">
        <transition v-for="(component, index) in components"
                    :key="`${component.id}-${index}`"
                    appear
                    name="modal"
        >
            <div v-if="component.active"
                 class="modal"
                 :class="[component.zIndex,`modal--${component.type}`]"
                 :style="{
                     'transition-duration': transitionDuration
                 }"
            >
                <div class="modal-content">
                    <component :is="component.component"
                               ref="componentRef"
                               v-bind="component.props"
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
        const components = reactive([]);
        const componentRef = ref(null);

        function open(component) {
            return new Promise((resolve, reject) => {
                const id = Math.random().toString(16).slice(2);
                component.id = id;
                component.zIndex = 1000 + components.length;
                component.active = true;
                component.type = component.type || 'vertical';
                components.push(component);

                app.back.on(`modal-${id}`, () => {
                    close(components.length - 1);
                });

                setTimeout(() => {
                    const index = components.findIndex(component => component.id === id);
                    resolve(componentRef.value[index]);
                }, props.transition);
            });
        }

        function close(index) {
            const component = components[index];
            if (!component) {
                return;
            }
            component.active = false;
            app.back.off(`modal-${component.id}`);
            setTimeout(() => {
                if (typeof component.onClose === 'function') {
                    component.onClose();
                }
                components.splice(index, 1);
            }, props.transition);
        }

        app.register('modal', open).then((options) => {
            // do something if you want
        });

        return {
            componentRef,
            components,
            close,
            transitionDuration: computed(() => {
                return `${props.transition / 1000}s`;
            })
        }
    }
}
</script>
