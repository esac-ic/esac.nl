<template>
    <div class="col-lg-4 col-md-6 d-flex flex-wrap">
        <div class="card w-100 position-relative">
            <a :href="agenda.url">
                <img class="card-img-top" :src="agenda.thumbnail" alt="Card image cap">
            </a>
            <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{agenda.startDate}}</span>
            <div class="card-body">
                <a :href="agenda.url">
                    <h4 class="card-title">{{agenda.title}}</h4>
                    <p class="card-text text-body">{{agenda.text}}</p>
                </a>
            </div>
            <div v-if="agenda.formId" class="card-footer bg-white p-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto text-muted">
                        <span class="ion-person-stalker"></span> {{agenda.amountOfPeopleRegisterd}}
                    </div>
                    <div v-show="agenda.canRegister" class="col-auto">
                        <form v-if="checkIfCurrentUserSignedUp()" method="POST" :action="agenda.registrationUrl">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="btn btn-outline-danger">Unregister</button>
                        </form>
                        <a v-if="!checkIfCurrentUserSignedUp()" class="btn btn-outline-primary" :href="agenda.registrationUrl">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        name: "AgendaItem",
        props: [
            'agenda',
        ],
        methods: {
            checkIfCurrentUserSignedUp() {
                return this.agenda.currentUserSignedUp;
            }
        },
        data() {
            return {
                csrfToken: window.Laravel.csrfToken,
            }
        },
    }
</script>

<style scoped>

</style>