<template>
    <VPage>

        <div class="tree w-full h-full flex flex-col justify-center">
            <div v-for="(layer, layerIndex) in tree"
                 :key="`layer-${layerIndex}`"
                 class="tree__layer w-full flex justify-around"
            >
                <div v-for="(memberLayer, memberLayerIndex) in layer"
                     :key="`member-layer-${memberLayerIndex}`"
                     class="tree__member-layer w-full flex justify-around"
                >
                    <Avatar v-for="(member, memberIndex) in memberLayer"
                            :key="`member-${memberIndex}`"
                            :subject="member"
                            class="w-14 h-14"
                            :class="{
                                'border-2 solid': layerIndex === currentLayerIndex && memberLayerIndex === currentMemberLayerIndex && memberIndex === currentMemberIndex
                            }"
                            @click="select(layerIndex, memberLayerIndex, memberIndex)"
                    />
                </div>
            </div>
        </div>

        <div class="flex justify-around">
            <VButton basic shadow @click="addParent">Add parent</VButton>
            <VButton basic shadow @click="addChildren">Add children</VButton>
            <VButton basic shadow @click="addWife">Add h/w</VButton>
            <VButton basic shadow @click="addBrotherOrSister">Add b/s</VButton>
        </div>

        <FamilyCreateFooter
            class="px-10"
            :progress="progress"
            @back="emit('back')"
        />
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import FamilyCreateFooter from '@/Components/Family/Create/Footer';
import Avatar from '@/Elements/Avatar';
import {inject, reactive, ref} from 'vue';
import VButton from '@/Elements/Button';

export default {
    name: 'FamilyMembers',
    components: {
        VButton,
        Avatar,
        FamilyCreateFooter,
        VPage
    },
    props: {
        members: Array,
        progress: Boolean,
    },
    emits: ['back'],
    setup(props, { emit }) {
        const currentMemberIndex = ref(0);
        const currentMemberLayerIndex = ref(0);
        const currentLayerIndex = ref(0);
        const members = reactive([
            { id: 1, name: 'Juozas'},
            { id: 2, name: 'Galina'},
            { id: 3, name: 'Julius'},
            { id: 4, name: 'Danutė'},
            { id: 5, name: 'Ignas'},
            { id: 6, name: 'Ramūnas'},
            { id: 7, name: 'Adomas'},
            { id: 8, name: 'Lukas'},
            { id: 9, name: 'Amelija'},
            { id: 10, name: 'Kajus'},
            { id: 11, name: 'Danielė'},
            { id: 12, name: 'Angelina'},
            { id: 13, name: 'Kotryna'},
            { id: 14, name: 'Agnė'},
        ])
        const tree = reactive([
            [
                {
                    people: [1,2],
                    children: [12],
                },
                {
                    people: [3,4],
                    children: [
                        {
                            people: [5,12],
                            children: [
                                {
                                    people: [8],
                                    children: [],
                                },
                                {
                                    people: [9],
                                    children: [],
                                }
                            ],
                        },{
                            people: [6,14],
                            children: [
                                {
                                    people: [10],
                                    children: [],
                                }, {
                                    people: [11],
                                    children: [],
                                }
                            ],
                        },{
                            people: [7,13],
                            children: [],
                        }
                    ],
                },
            ],
        ])

        return {
            emit,
            tree,
            currentMemberIndex,
            currentMemberLayerIndex,
            currentLayerIndex,
            select(layerIndex, memberLayerIndex, memberIndex) {
                currentLayerIndex.value = layerIndex;
                currentMemberLayerIndex.value = memberLayerIndex;
                currentMemberIndex.value = memberIndex;
            },
            addParent() {
                let index = currentLayerIndex.value - 1;
                if (!Array.isArray(tree[index])) {
                    tree.unshift([]);
                    currentLayerIndex.value++;
                    index++;
                }

                for (let x = tree[index].length; x < tree[currentLayerIndex.value].length; x++) {
                    tree[index].push([]);
                }

                tree[index][currentMemberLayerIndex.value].push({ name: 'Parent' });
            },
            addWife() {
                tree[currentLayerIndex.value][currentMemberLayerIndex.value].push({
                    name: 'wife'
                });
            },
            addChildren() {

            },
            addBrotherOrSister() {

            }
        };
    },
}
</script>
