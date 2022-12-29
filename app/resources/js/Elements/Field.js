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
};

const useField = (props) => {
    const t = useI18n().t;
    const errorMessage = computed(() => {
        if (!props.error && !props.errors) return null;
        if (props.error) return props.error;

        let key = props.name.replaceAll('][', '.');
        key = key.replaceAll('[', '.');
        key = key.replaceAll(']', '');

        return props.errors[key];
    });

    return {
        errorMessage,
        label: computed(() => {
            return props.title || t(`field.title.${props.name}`);
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
