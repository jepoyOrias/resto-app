<template>
    <div>
                <v-container>
                    <div
                    >
                        <h2 class="text-center text-teal-darken-2 mb-10" data-aos-once="true" data-aos="fade-up-left" data-aos-offset="200" >Menus</h2>
                        <v-row>
                            <v-col cols="12" md="6" xl="3" 
                            v-for=" i in 12"
                            :key="i"
                            >
                                <v-card elevation="5"
                                data-aos="fade-up"
                                data-aos-duration="1000"
                                data-aos-offset="200"
                                data-aos-delay="50"
                                data-aos-easing="ease-in-out"
                                data-aos-once="true"
                                data-aos-anchor-placement="bottom"
                                
                                
                                >
                                    <v-card-text>
                                        <div>Word of the Day</div>
                                        <p class="text-h4 text--primary">
                                            be•nev•o•lent
                                        </p>
                                        <p>adjective</p>
                                        <div class="text--primary">
                                            well meaning and kindly.<br>
                                            "a benevolent smile"
                                        </div>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn variant="text" color="deep-purple-accent-4">
                                            Learn More
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>


                </v-container>
            </div>
</template>
<script>
</script>

<style>

</style>