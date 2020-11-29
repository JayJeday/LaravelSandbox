<template>
    <div>
        <v-container fill-height>
            <v-row>
                <v-col align="center" justify="center">
                    <v-btn color="#408ec6" @click="this.onClickUploadBtn" x-large>Upload Image</v-btn>
                </v-col>
                <v-col align="right" justify="center">
                    <v-radio-group
                        row
                        dense
                        v-model="imageSize"
                    >
                        <v-radio
                            label="Small"
                            color="#7a2048"
                            value="small"
                        ></v-radio>
                        <v-radio
                            label="Medium"
                            value="medium"
                            color="#7a2048"
                        ></v-radio>
                    </v-radio-group>
                </v-col>
            </v-row>

            <v-col align="center" justify="center"
                   v-for="n in filterImage"
                   :key="n.id"
                   class="d-flex child-flex"
                   cols="3">

                <v-img
                    :src="n.src"
                    :alt="n.alt"
                    aspect-ratio="1"
                    :width="imageWidth"
                    class="grey lighten-2">

                    <template v-slot:placeholder>
                        <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center">
                            <v-progress-circular
                                indeterminate
                                color="grey lighten-5"
                            ></v-progress-circular>
                        </v-row>
                    </template>
                </v-img>
            </v-col>
            <v-row>

            </v-row>
            <input
                type="file"
                id="file"
                accept="image/*"
                style="display: none"
                ref="imageUpload"
                @change="handleImageUpload()">
        </v-container>
        <v-progress-linear
            class="progressLoc"
            height="10"
            indeterminate
            :active="isLoading"
            color="#7a2048"
        ></v-progress-linear>
    </div>

</template>

<script>
export default {
    name: "ImageUploader",
    mounted() {
        this.getServerImages();
    },
    data: () => ({
        images: [],
        filterImage:[],
        imageWidth:'400',
        imageSize:'medium',
        currentSearchSize:'_400_',
        isLoading: false
    }),
    watch:{
      imageSize:function (){
          if(this.imageSize === "small"){
              this.currentSearchSize='_100_';
              this.imageWidth = "100";
          }

          if(this.imageSize === "medium"){
              this.currentSearchSize='_400_';
              this.imageWidth = "400";
          }

          this.filterImage = this.images.filter((image)=>{
              return image.src.includes(this.currentSearchSize);
          });
      }
    },
    methods: {
        /**
         * Get images from server
         */
        getServerImages() {
            this.isLoading = true;
            axios.get(`api/images`)
                .then(response => {
                        this.images = this.formatImagesSrc(response.data);
                        /*
                           filter image the default size
                         */
                        this.filterImage = this.images.filter((image)=>{
                            return image.src.includes(this.currentSearchSize);
                        });
                        this.isLoading = false;
                    }
                ).catch((error) => {
                console.log('error', error);
                this.isLoading = false;
            });
        },
        /**
         * map object to ui component
         * @param {array} image data
         * @return {array} image object with src url
         */
        formatImagesSrc(images = []) {
            return images.map((image) => {
                return {
                    id: image.id,
                    src: "https://storage.googleapis.com/" + image.bucket + "/" + image.name,
                    alt: image.name
                }
            })
        },
        onClickUploadBtn() {
            this.$refs.imageUpload.click();
        },
        handleImageUpload() {
            let image = this.$refs.imageUpload.files[0];
            this.uploadImage(image);
            //reset value
            $("#file")[0].value = '';
        },
        /**
         * Upload image
         * @param {object} image data
         */
        uploadImage(image) {
            let data = new FormData();
            data.append('image', image);
            this.isLoading = true;
            axios.post(`api/images`, data,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response) => {
                    //add image to images array to display it
                    response.data[0].forEach((image)=>{
                       this.images.push(
                           {
                           id: image.id,
                           src: "https://storage.googleapis.com/" + image.bucket + "/" + image.name,
                           alt: image.name
                       });

                        this.filterImage = this.images.filter((image)=>{
                            return image.src.includes(this.currentSearchSize);
                        });
                    });
                this.isLoading = false;
            }).catch((error) => {
                console.log('error', error.response.data.message);
                this.$swal.fire({
                    type: 'error',
                    title: error.response.data.message,
                    showConfirmButton: false,
                    timer: 1400
                });
                this.isLoading = false;
            });
        },
    }
}
</script>

<style scoped>
.progressLoc {
    top: 0px;
    position: absolute;
}
</style>
