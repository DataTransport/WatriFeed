<template>
    <div>

        <div class="row">

            <div class="col-lg-3">
                <h1 class="my-4">
                    <input type="text" v-model="$root.$data.search$" placeholder="Search.." class="form-control">
                </h1>
                <div class="my-4">
                    <div class="checkbox" v-for="category in $root.$data.categories">
                        <label>
                            <input type="checkbox" name="category" :value="category"
                                   v-model="$root.$data.selectedCategory"> {{category}}
                        </label>
                    </div>
                </div>
                <div class="list-group">
                    <a v-for="(location, index) in $root.fuzzySearch" @click="getLocation(location)"
                       class="list-group-item">{{location.text}}</a>
                </div>
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">
                <div class="card mt-4">
                    <v-map ref="map" id='map' :zoom="zoom" :maxZoom="tileProvider.maxZoom" :center="center">
                        <v-tilelayer ref="tile" :url="tileProvider.url.osm2"
                                     :attribution="tileProvider.attribution"></v-tilelayer>
                        <v-marker-cluster ref="markerCluster" :options="clusterOptions">
<!--                            <v-marker v-for="l in  $root.fuzzySearch" :key="l.id" :lat-lng="l.latlng" ref="item" :icon="$root.iconMarker(l.image)">-->
<!--                                <v-popup :content="l.text"></v-popup>-->
<!--                            </v-marker>-->
                            <v-marker v-for="l in  $root.fuzzySearch" :key="l.id" :lat-lng="l.latlng" ref="item" :icon="getIconNumber(l.id)">
                                <v-popup :content="l.bodyPopup"></v-popup>
                            </v-marker>
                        </v-marker-cluster>
                    </v-map>
                    <div v-if="cardBody.text" class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <a href="#">
                                    <img id="card-card-image-size" class="img-fluid rounded mb-3 mb-md-0"
                                         :src="'/images/' + cardBody.image + '.jpg'" alt="">
                                </a>
                            </div>
                            <div class="col-md-5">
                                <h3 class="card-title">{{cardBody.text}}</h3>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi
                                    assumenda doloribus excepturi laborum minima nesciunt placeat recusandae rem
                                    repellendus? Amet assumenda aut doloribus eaque error illo, libero neque nihil
                                    quis?</p>
                                <a class="btn btn-primary" href="">View</a>
                            </div>
                        </div>

                    </div>
                    <!--<img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">-->
                </div>
                <!--                <div class="card card-outline-secondary my-4">-->
                <!--                    <div class="card-header">-->
                <!--                        Chart-->
                <!--                    </div>-->
                <!--                    <div class="card-body">-->
                <!--                        <div style="height:400px">-->
                <!--                            <chart-pie :data='$root.chartData' :config='$root.$data.chartConfig'></chart-pie>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!-- /.card -->
            </div>
            <!-- /.col-lg-9 -->
        </div>
    </div>
</template>

<script>
    import {ChartPie} from 'vue-d2b'

    export default {
        name: "TestComponent",
        components: {
            ChartPie
        },
        data() {
            return {
                cardBody: {
                    id: null,
                    latlng: null,
                    text: null,
                    image: null
                },
                clusterOptions: {},
                initialLocation: L.latLng(12.606765, -8.010009),
                marker: L.latLng(47.413220, -1.219482), //marker
//                v-map props
                zoom: 13,
                // center: [-34.9205, -57.953646],
                center: [12.606765, -8.010009],
//                tile provider
                tileProvider: {
                    name: 'Satelite',
                    url: {
                        arcgis: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                        osm1: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
                        osm2: 'http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png'
                    },
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 18
                },



            }
        },
        methods: {
            getLocation(location) {
                const vm = this;
                const index = _.findIndex(vm.$root.$data.locations, ['id', location.id]);
                const marker = vm.$refs.item[index].mapObject;
                const position = location.latlng;
                const map = vm.$refs.map.mapObject;
                vm.$refs.markerCluster.mapObject.zoomToShowLayer(marker, function () {
                    vm.cardBody = location;
                    return marker.openPopup();
                });

            },

            // methode pour avoir une icon numerotée

            getIconNumber(number,color='black'){
                return  L.ExtraMarkers.icon({
                    icon: 'fa-number',
                    number: number,
                    shape: 'square',
                    markerColor:  color
                });
            }
        },
        mounted() {
            var vm = this;
            setTimeout(function () {
                console.log("done");
                vm.$nextTick(function () {
                    vm.clusterOptions = {disableClusteringAtZoom: 11};
                });
            }, 5000);
            var map = vm.$refs.map.mapObject; // ref is used to register a reference to an element or a child component.
            map.on('popupopen', function (e) {
                var px = map.project(e.popup._latlng);
                px.y -= e.popup._container.clientHeight / 2;
                map.panTo(map.unproject(px), {animate: true});
            });


        }
    }
</script>

<style>
    #map {
        height: 50vh;
        width: 900px;
        margin: 0;
    }

    #card-card-image-size {
        height: 300px;
        width: 700px;
        margin: 0;
    }

    .image-icon img {
        height: 52px !important;
        width: 52px !important;
        border-radius: 50%;
        border: solid;
        border-color: #32CD32;
    }



</style>
