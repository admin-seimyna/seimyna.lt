<template>
    <VPage>
        <FamilyTree />
    </VPage>
</template>
<script>
import VButton from '@/Elements/Button';
import VPage from '@/Components/Layout/Page';
import FamilyTree from '@/Elements/Family/Tree';

export default {
    name: 'FamilyCreate',
    components: {
        FamilyTree,
        VPage,
        VButton
    },
}
</script>
