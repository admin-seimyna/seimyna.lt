<template>
    <div class="w-full flex justify-around">
        <div v-for="(branch, index) in branches"
             :key="`branch-${index}`"
             class="flex w-full flex-col"
        >
            <div class="flex w-full items-center justify-around">
                <div v-for="member in branch.people"
                     :key="`${index}-member-${member.id}`"
                     class="flex flex-col"
                >
                    <Avatar :subject="member"
                            class="w-14 h-14"
                    />
                </div>
            </div>

            <TreeBranch
                v-if="branch.children && branch.children.length"
                :tree="branch.children"
                :members="members"
            />
        </div>
    </div>
</template>
<script>
import Avatar from '@/Elements/Avatar';
import {computed} from 'vue';
export default {
    name: 'TreeBranch',
    components: {Avatar},
    props: {
        tree: Array,
        members: Array,
    },
    setup(props) {
        return {
            branches: computed(() => {
                return [...props.tree].map((branch) => {
                    branch.people = branch.people.map((memberId) => {
                        return props.members.find(m => m.id === memberId);
                    });
                    return branch;
                });
            }),
        }
    }
}
</script>
