<template>
    <div class="bg-teal-darken-2">
        <div class="bg-wave">
            <v-container>
                <div class="position-relative mt-16">
                    <v-row class="d-flex align-center text-center">
                        <v-col cols="12"
                         md="8"
                         data-aos="slide-right"
                         data-aos-duration="1000"
                         data-aos-offset="200"
                            data-aos-delay="50"
                            data-aos-easing="ease-in-out"
                            data-aos-mirror="true"
                            data-aos-once="false"
                         >
                            <h1 class="text-white">
                                Discover a culinary paradise <br>
                                and Indulge in culinary perfection</h1>
                            <v-btn class="flex-grow-1 text-white text-none" rounded="0" variant="plain"
                                @click="dialog = false" prepend-icon="mdi-food">
                                Order now
                            </v-btn>

                            <v-btn class="text-white flex-grow-1 text-none" color="primary" rounded="0" variant="flat"
                                @click="dialog = false" prepend-icon="mdi-store-clock">
                                Make a reservation
                            </v-btn>
                        </v-col>
                        <v-col cols="12"
                         md="4"  
                            data-aos="fade-up"
                            data-aos-duration="1000"
                            data-aos-offset="200"
                            data-aos-delay="50"
                            data-aos-easing="ease-in-out"
                            data-aos-once="false"
                            data-aos-mirror="true"
                            data-aos-anchor-placement="bottom">
                            <v-img 
                          
                            src="/Images/burger-no-bg.png" 
                            height="650"
                             contain 
                             alt="test
                             " max-width="550"
                                max-height="650">
                            </v-img>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </div>
    </div>
</template>

<script>
</script>

<style>

</style>