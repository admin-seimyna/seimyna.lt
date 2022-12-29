<template>
    <VPage>

        <Tree :tree="tree"
              :members="members"
        />

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
import Tree from '@/Elements/Tree/Tree';

export default {
    name: 'FamilyMembers',
    components: {
        Tree,
        VButton,
        Avatar,
        FamilyCreateFooter,
        VPage
    },
    props: {
        progress: Boolean,
    },
    emits: ['back'],
    setup(props, { emit }) {
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
            {
                people: [1,2],
                children: [
                    {
                        people: [12],
                        children: [],
                    }
                ],
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
        ])

        return {
            emit,
            tree,
            members,
        };
    },
}
</script>
