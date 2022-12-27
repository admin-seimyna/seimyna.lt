<template>
    <div class="w-full h-full flex flex-col">
        <TreeBranch :tree="tree"
                    :members="members"
        />
    </div>
</template>
<script>
import Avatar from '@/Elements/Avatar';
import TreeBranch from '@/Elements/Tree/TreeBranch';
export default {
    name: 'Tree',
    components: {TreeBranch, Avatar},
    props: {
        tree: Object,
        members: Object,
    }
}
</script>
