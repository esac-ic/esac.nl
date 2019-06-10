<template>
    <div class="form-group">
        <input type="text" :id="name" :name="name" :placeholder="placeholder" autocomplete="off" v-model="text"
               v-on:keyup="loadItems" class="form-control" :list="'data-list-' + name">
        <datalist :id="'data-list-' + name">
            <option v-for="(item,key) in dataList" :key="key">{{ item.text }}</option>
        </datalist>
    </div>
</template>

<script>
    import axios from 'axios/index';

    export default {
        name: 'AutoCompleteField',
        data(){
            return {
                dataList: [],
                text: ""
            }
        },
        props: [
            'name',
            'placeholder',
            'value',
            'url'
        ],
        methods:{
            loadItems(){
                if(this.text.length > 3){
                    let url = this.url + "?term=" + this.text;
                    axios
                        .get(url)
                        .then(response => {
                            this.dataList = response.data;
                        });
                }
            }
        },
        mounted(){
            this.text = this.value;
        }
    };
</script>