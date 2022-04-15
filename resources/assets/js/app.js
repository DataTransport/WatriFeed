
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue2Leaflet from 'vue2-leaflet';
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import LeafletExtraMarkers from 'leaflet-extra-markers'
import App from './components/App.vue'
import TestComponent from './components/TestComponent'

Vue.component('v-map', Vue2Leaflet.LMap);
Vue.component('v-tilelayer', Vue2Leaflet.LTileLayer);
Vue.component('v-marker', Vue2Leaflet.LMarker);
Vue.component('v-circle', Vue2Leaflet.LCircle);
Vue.component('v-popup', Vue2Leaflet.LPopup);
Vue.component('v-marker-cluster', Vue2LeafletMarkerCluster);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



function rand(n) {
    let max = n + 0.1;
    let min = n - 0.1;
    return Math.random() * (max - min) + min;
}
function random_item(items) {
    return items[Math.floor(Math.random() * items.length)];
}

new Vue({
    data(){

        // let locations = [];
        let categories = ['Shrub', 'Herb (Perrenial)', 'Tree'];

        // for (let i = 1; i < 10; i++) {
        //     locations.push({
        //         id: i,
        //         // latlng: L.latLng(rand(-34.9205), rand(-57.953646)),
        //         latlng: L.latLng(rand(12.606765), rand(-8.010009)),
        //         text: 'Hola ' + i ,
        //         bodyPopup: `Hola ${i} <hr> cool`,
        //         image: `placeimg_36_36_nature (${i})`,
        //         category: random_item(categories)
        //     })
        // }
        return {
            chartConfig (chart) {
                chart.donutRatio(0.5)
            },
            selectedCategory: [],
            categories: ['Shrub', 'Herb (Perrenial)', 'Tree'],
            search$: '',
            locations:[],
            //use fusejs
            fuse: null,
            fuseOptions: {
                shouldSort: true,
                threshold: 0.6,
                location: 0,
                distance: 100,
                maxPatternLength: 32,
                minMatchCharLength: 1,
                keys: [
                    "text"
                ]
            },
        }
    },
    mounted(){
        const vm = this;
        axios.get('/stoptime').then(response =>{
            for (let i = 1; i < 10; i++) {
                vm.locations.push({
                    id: i,
                    latlng: L.latLng(rand(12.606765), rand(-8.010009)),
                    text: 'Hola ' + i ,
                    bodyPopup: `Hola ${i} <hr> cool`,
                    image: `placeimg_36_36_nature (${i})`,
                    category: random_item(vm.categories)
                })
            }
            vm.fuse = new window.Fuse(vm.locations, vm.fuseOptions);
        })





    },
    computed: {
        chartData(){
            var vm = this;
            return _(vm.fuzzySearch)
                .countBy("category")
                .map(function (count, name) {
                    return {label: name, value: count};
                })
                .value();
        },
        fuzzySearch(){
            const vm = this;
            let selected$;
            const search$ = vm.search$.trim() === '' ? vm.locations : vm.fuse.search(vm.search$.trim());
            const selectedFilter = vm.multiFilter(search$, {
                category: vm.selectedCategory,
            });
            if (_.isEmpty(vm.selectedCategory) && vm.search$.trim() === '') {
                selected$ = vm.locations
            } else {
                selected$ = selectedFilter
            }
            return selected$
        },
    },
    methods: {
        multiFilter(array, filters) {
            let filterKeys = Object.keys(filters);
            return array.filter(function (eachObj) {
                return filterKeys.every(function (eachKey) {
                    if (!filters[eachKey].length) {
                        return true; // passing an empty filter means that filter is ignored.
                    }
                    return _.isArray(eachObj[eachKey]) ?
                        eachObj[eachKey].some(function (o) {
                            return filters[eachKey].includes(o.name);
                        })
                        : filters[eachKey].includes(_.isObject(eachObj[eachKey]) ? eachObj[eachKey].name : eachObj[eachKey])
                });
            });
        },
        iconMarker(item){
            if (_.isEmpty(item)) {

            } else {
                return L.divIcon({
                    html: `<img style="width: 100%;" src="/images/${item}.jpg"/>`,
                    // Specify a class name we can refer to in CSS.
                    className: 'image-icon',
                    // Set a markers width and height.
                    iconSize: [36, 36]
                })
            }
        },
        click: function (e) {
            alert("clusterclick")
        }
    },
    el: '#app',
    render: (function (h) {
        return h(TestComponent);
    })
});
