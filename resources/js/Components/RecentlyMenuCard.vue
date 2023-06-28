<template>

                <v-row class="mx-auto">
                <v-col cols="12" class="mx-auto ">
                    <h3 class="text-h6 text-primary mt-0">Recently Added Menu</h3>
                </v-col>
                <v-col cols="12" sm="6" xl="4" v-for="(item ,index) in menuItem" :key="index">
                    
                    <v-card class="d-flex flex-column shadow-none fill-height" elevation="12">
                            
                            <v-img cover height="180" :src="showImage() + item.image"></v-img>
                            <v-card-title class="text-capitalize d-flex justify-between flex-wrap my-auto">
                                <span class="text-no-wrap w-75 text-subtitle" >{{SubStringDescription(item.name, 25)}}</span>
                                <v-spacer></v-spacer>
                                <v-btn
                                    :icon="item.show ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                                    @click="item.show = !item.show"
                                    size="small"
                                    variant="flat"
                                ></v-btn>
                            </v-card-title>
                        <v-card-item >
                                <v-expand-transition v-show="item.show" class="mt-auto">
                                <div>
                                    <v-divider></v-divider>
                                    <v-card-text class="px-0  py-2">
                                        Price: ₱{{ item.price }}
                                    </v-card-text>
                                    <v-card-text class="px-0 py-2">
                                        Description : {{SubStringDescription(item.description, 100)}}
                                    </v-card-text>
                                    <v-card-text class="px-0  py-2">
                                        <span class="font-weight-light me-2 text-gray-darken-1">categories:</span>
                                        <v-chip  class="me-2 text-caption bg-blue-darken-3"
                                          v-for="category in item.categories" :key="category.id">
                                            {{ category.name }}
                                        </v-chip>
                                </v-card-text>
                                </div>
                                </v-expand-transition>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
</template>

<script setup>


const props = defineProps({
    menuItem: Object,
})

const showImage = () => {
    return "/storage/";
}
const SubStringDescription = (description, n = 100)=>{
  if (description.length <= n) { return description; }

  const subString = description.slice(0, n-1); // the original check
  return (description 
    ? subString.slice(0, subString.lastIndexOf(" ")) 
    : subString) + "...";
}

</script>