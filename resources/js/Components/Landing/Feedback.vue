<template>
  <div class="bg-teal-darken-2">
    <v-container>
        <h2 class="text-center">Customer's Feedback</h2>

        <v-row class="mt-16">
            <v-col cols="12" md="6" xl="3" 
            v-for=" i in 4"
            :key="i"
            class="d-flex">
                <v-card class="bg-transparent position-relative" elevation="0">
                    <v-img
                    cover
                    height="250"
                    src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                    ></v-img>
                    <v-card-text>
                        <h4>Marcus James</h4>
                        <v-rating
                            :model-value="4.5"
                            color="primary"
                            density="compact"
                            half-increments
                            readonly
                            size="small"
                            ></v-rating>
                            <p>I recently had the pleasure of dining at CrunchCrave, and it was an experience that left me craving for more. From the moment I stepped foot into the restaurant until the last bite, everything about CrunchCrave exceeded my expectations.</p>
                    </v-card-text>
                    <v-icon class="quote" color="warning">mdi-format-quote-open</v-icon>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
  </div>
</template>

<script>
export default {

}
</script>

<style>
.quote{
    position: absolute;
    top:50%;
    right: 10px;
    font-size: 200px;
    z-index: -1;
    opacity: 0.5;
}
</style>