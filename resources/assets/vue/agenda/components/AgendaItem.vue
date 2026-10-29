<template>
    <div class="col-lg-4 col-md-6 d-flex">
        <div class="card w-100 position-relative">
            <a :href="event.url">
                <img class="card-img-top" :src="event.thumbnail" alt="">
            </a>
            <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{ event.startDate }}</span>
            <div class="card-body">
                <a :href="event.url">
                    <h4 class="card-title">{{ event.title }}</h4>
                    <p class="card-text text-body">{{ event.text }}</p>
                </a>
            </div>
            <div v-if="event.hasRegistration" class="card-footer">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto text-muted">
                        <UserGroupIcon class="icon" aria-hidden="true" /> {{ event.registrationCount }}
                    </div>
                    <div class="col-auto">
                        <form v-if="event.currentUserSignedUp && event.registrationOpen" method="POST" :action="event.registrationUrl" data-confirm-title="Unregister" :data-confirm="'Unregister from ' + event.title + '?'" data-confirm-button="Unregister">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="btn btn-outline-danger">Unregister</button>
                        </form>
                        <a v-else-if="!event.currentUserSignedUp && event.canRegister" class="btn btn-outline-primary" :href="event.registrationUrl">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { UserGroupIcon } from '@heroicons/vue/24/outline';

    export default {
        name: 'AgendaItem',
        components: { UserGroupIcon },
        props: {
            event: Object,
        },
        data() {
            return { csrfToken: document.querySelector('meta[name="csrf-token"]').content };
        },
    };
</script>
