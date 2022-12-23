<template>
    <div class="family-tree w-full h-full flex flex-col items-center justify-center">
        <div v-for="(layer, layerIndex) in tree"
             :key="`layer-${layerIndex}`"
             class="family-tree__layer flex items-center w-full justify-center"
        >
            <div v-for="(members, memberLayerIndex) in layer"
                 :key="`member-layer-${memberLayerIndex}`"
                 class="flex items-center w-full justify-center"
            >
                <avatar v-for="(member, memberIndex) in members"
                        :key="`member-${memberIndex}`"
                        :subject="member"
                        class="w-10 h-10"
                        @click="addParents(layerIndex, memberLayerIndex, memberIndex)"
                />
            </div>
        </div>
    </div>
</template>
<script>
import {reactive, ref} from 'vue';
import Avatar from '@/Elements/Avatar';
import FamilyTreeMember from '@/Elements/Family/Member';
import FamilyTreeButton from '@/Elements/Family/Button';

export default {
    name: 'FamilyTree',
    components: {FamilyTreeMember, FamilyTreeButton, Avatar},
    setup(props) {
        let tree = reactive([
            [
                [
                    {
                        name: 'Ignas',
                    }
                ]
            ],
        ]);

        const currentLayer = ref(1);
        const currentMember = ref(0);

        return {
            tree,
            currentLayer,
            currentMember,
            addParents(layerIndex, memberLayerIndex, memberIndex) {
                const member = { name: 'test'};
                let index = layerIndex - 1;
                if (!Array.isArray(tree[index])) {
                    tree.unshift([]);
                    index++;
                }

                for(let x = tree[index].length; x < memberIndex; x++) {
                    tree[index].push([]);
                }

                tree[index][memberIndex].push(member);
            }
        }
    }
}
</script>
