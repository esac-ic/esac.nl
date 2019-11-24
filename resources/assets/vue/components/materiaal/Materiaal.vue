<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
    <!--               Toggle Zomer/Winter lijst         -->
                    <span v-if="summer">
                        <button v-on:click="summer = !summer">Ga naar winter</button>
                    </span>
                    <span v-show="!summer">
                        <button v-on:click="summer = !summer">Ga naar zomer</button>
                    </span>


    <!--                Lijssie               -->
                    <span v-show="summer">

                        <ul>
                            <li v-for="place in summerplaces" v-on:click="updateSelection(place)" class="mat-list list-group-item">
                                {{place.number}}
                                <InputSpinner
                                        max={10}
                                        min={2}
                                        step={2}
                                        colorMax={"#f04048"}
                                        colorMin={"#40c5f4"} />
                            </li>
                        </ul>

                    </span>

                    <span v-show="!summer">
                        <ul>
                            <li v-for="place in winterplaces" v-on:click="updateSelection(place)" class="mat-list list-group-item">
                                {{place.number}}
                                <input type="number" value="0" min="0" max="10"/>
                            </li>
                        </ul>
                    </span>

                </div>

                <div class="col-md-6">
    <!--                Datum selectie deel   -->
                    <h1>
                        <ul>
                            <li v-for="place in selectedplaces">
                                {{place.number}}
                            </li>
                        </ul>
                    </h1>

                </div>
            </div>

            <div class="row">
    <!--                Gooi submit             -->
            </div>

            <div class="row">
    <!--                Huidige selectie/mandje    -->
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            summer: Boolean,
        },
        data () {
            return {
                summerplaces: [
                    {number: 'First Place'},
                    {number: 'Second Place'},
                    {number: 'Third Place'},
                    {number: 'Fourth Place'},
                    {number: 'Fifth Place'},
                    {number: 'Sixth Place'},
                    {number: 'Seventh Place'},
                    {number: 'Eighth Place'},
                    {number: 'Ninth Place'},
                    {number: 'Tenth Place'},
                ],
                winterplaces: [
                    {number: 'First Winter'},
                    {number: 'Second Winter'},
                    {number: 'Third Winter'},
                    {number: 'Fourth Winter'},
                    {number: 'Fifth Winter'},
                    {number: 'Sixth Winter'},
                    {number: 'Seventh Winter'},
                    {number: 'Eighth Winter'},
                    {number: 'Ninth Winter'},
                    {number: 'Tenth Winter'},
                ],
                selectedplaces: [],
            }
        },
        methods: {
            updateSelection(selected) {
                this.selectedplaces.push(selected);
            }
        },
    }
</script>
<style>

    .list-group-item {
        display: block;
        text-decoration: none;
        margin: 1em 0.2em;
        color: #4a4a4a;
    }

    .list-group-item:hover {
        color: red;
    }

    .highlight {
        color: blue;
    }
</style>