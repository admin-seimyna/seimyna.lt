import {computed} from 'vue';
import {useI18n} from 'vue-i18n';

const fieldProps = {
    nameKey: {
        type: String,
        default: 'name',
    },
    valueKey: {
        type: String,
        default: 'id'
    },
    name: String,
    error: String,
    errors: Object,
    title: String,
    disableTitle: Boolean,
};

const useField = (props) => {
    const t = useI18n().t;
    const errorMessage = computed(() => {
        if (!props.error && !props.errors) return null;
        if (props.error) return props.error;
        if (!props.name) return null;

        let key = props.name.replaceAll('][', '.');
        key = key.replaceAll('[', '.');
        key = key.replaceAll(']', '');

        return props.errors[key];
    });

    return {
        errorMessage,
        label: computed(() => {
            if (props.disableTitle) return;
            return props.title || (props.name ? t(`field.title.${props.name}`) : null);
        }),
        hasError: computed(() => {
            return !!errorMessage.value;
        }),
        placeholder: computed(() => {
            return `${props.placeholder || t('field.placeholder.insert')}...`;
        }),
    }
}

export { fieldProps, useField };
