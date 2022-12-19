<template>
    <div class="family-tree w-full h-full flex flex-col items-center justify-center">
        <div v-for="(layer, layerIndex) in family"
             :key="`layer-${layerIndex}`"
             :class="{
                 'z-10': currentLayer === layerIndex,
                 'opacity-30': currentMember !== null && currentLayer !== layerIndex
             }"
             class="w-full flex items-center justify-center"
        >
            <template v-if="!layer.length">
                <FamilyTreeButton
                    inline
                    @click="addMember(layerIndex)"
                />
            </template>
            <template v-else>
                <FamilyTreeMember
                    v-for="(member, memberIndex) in layer"
                    :key="`member-${memberIndex}`"
                    :member="member"
                    :active="layerIndex === currentLayer && currentMember === memberIndex"
                    @add="addMember(currentLayer)"
                    @remove="removeMember(currentLayer, currentMember)"
                    @select="selectMember(memberIndex, layerIndex)"
                />
                <FamilyTreeButton
                    inline
                    @click="addMember(layerIndex)"
                />
            </template>
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
        let family = reactive([
            [],
            [
                {
                    name: 'Ignas',
                }
            ],
            [],
        ]);

        const currentLayer = ref(null);
        const currentMember = ref(null);

        function blur() {
            currentMember.value = null;
        }

        function addParentsLayer() {
            family.unshift([]);
            blur();
        }

        function addChildrenLayer() {
            family.push([]);
            blur();
        }

        return {
            family,
            currentLayer,
            currentMember,
            addParentsLayer,
            addChildrenLayer,
            selectMember(index, layerIndex) {
                if (currentMember.value === index) {
                    currentMember.value = null;
                    return;
                }
                currentMember.value = index;
                currentLayer.value = layerIndex;
            },


            removeLayer(index) {
                family.splice(index, 1);
            },
            addMember(layerIndex) {
                if (typeof layerIndex === 'undefined' || layerIndex === null) {
                    layerIndex = 0;
                }

                family[layerIndex].push({
                    name: 'Test',
                });

                if (!layerIndex) {
                    addParentsLayer();
                }

                const length = family.length - 1;
                if (layerIndex === length) {
                    addChildrenLayer();
                }

                blur();
            },
            removeMember(layerIndex, memberIndex) {
                family[layerIndex].splice(memberIndex, 1);
                if (!family[layerIndex].length) {
                    family.splice(layerIndex, 1);
                }
                blur();
            },
        }
    }
}
</script>
