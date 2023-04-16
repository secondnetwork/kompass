
import * as geodata from '../getjson'
import * as map from '../map'
import Fuse from 'fuse.js'

window._ = require("lodash");
window.search = {
    items: null,
    fuse: null,
    fonds: [],
    fonds_arrey: ['ELER','EFRE','ESF'],
    regionen_arrey: ['ELER','EFRE','ESF'],
    query: "",
    results: [],
    EFRE: false,
    ESF: false,

    pageNumber: 0,
    size: 10,
    total: "",

    init() {
        let url = new URL(window.location);
        url.pathname = geodata.datajson;


        fetch(url.toString())
            .then((response) => response.json())
            .then((items) => {
                this.items = items;

                this.fuse = new Fuse(this.items, {
                    includeScore: true,
                    isCaseSensitive:true,
                    // includeMatches: true,
                    // isCaseSensitive: true,
                    minMatchCharLength: 3,
                    // ignoreFieldNorm :  true,
                    // ignoreLocation:true,

                
        
                    keys: [
                        {
                            name: 'title',
                            weight: 2
                          },
                
                      'type'
                    ]
                });
     
 
            })
            .catch(console.error);
   
    },
    search() {
        if (this.query == '') {


            this.results = this.items;
            // afterFilter(this.items)
            return false;
        }

        console.log('suche');
        

        this.results = _(this.fuse.search(this.query)).map((r) => r.item)
        .orderBy("score", "desc")
        .value();
        console.log(this.results);
        // afterFilter(this.results);
        // console.log(_.find(test, 'Battery LabFactory der TU Braunschweig' ));
          // .orderBy("score", "desc")
          // .take(1000)
          // .value());
         
            // afterFilter(_.find(this.items, { 'title': this.query }).map((r) => r.item)
            // .orderBy("score", "desc")
            // .take(1000)
            // .value())
        // afterFilter(_(this.fuse.search(this.query)).map((r) => r.item)
        //     .orderBy("score", "desc")
        //     .take(1000)
        //     .value())

    },
    fondsbox() {

        if (this.EFRE && this.ESF) {
            afterFilter(_.filter(this.results, {  'type' : 'EFRE','type' : 'ESF'}))
            console.log('ALLA');
            return false;
        }
        if (this.EFRE) {
            console.log(this.EFRE+'EFRE');
            afterFilter(_.filter(this.results, {  'type' : 'EFRE'}))
        }
        if (this.ESF) {
            console.log(this.ESF+'ESF');
            afterFilter(_.filter(this.results, {  'type' : 'ESF'}))
        }


    }
};


// const url = new URL(window.location);
// url.pathname = "datajson";
// url.searchParams.set("t", Date.now());

// const items = await fetch(url.toString())
//   .then((response) => response.json())
//   .catch(console.error);

//   const fuse = new Fuse(items, {
//     includeScore: true,
//     minMatchCharLength: 3,
//     keys: ["title", "description", "categories", "content"],
//   });

// function appendData (data) {
//     // console.log (data);
//    return(data);
//  }
// function getjsonmap(datajson) {
//     fetch (datajson).then (function (response) {
//         return response.json();
//      }).then (function (data) {
//        return data;
//      }).catch (function (error) {
//         console.log ("error: " + error);
//      });

// };

// const naff = getjsonmap(datajson)
// console.log(naff);

export default () => {




    console.log('filter');
    // let datamap = getjsonmap(datajson);
    // console.log(datamap);
    // return {

    //     items: null,
    //     fuse: null,
    //     query: "",
    //     results: [],
    //     init() {
    //       let url = new URL(window.location);
    //       url.pathname = "wp-test.json";
    //       url.searchParams.set("t", Date.now());

    //       fetch(url.toString())
    //         .then((response) => response.json())
    //         .then((items) => {
    //           this.items = items;

    //           this.fuse = new Fuse(this.items, {
    //             includeScore: true,
    //           //   minMatchCharLength: 3,
    //             keys: ["title", "type", "foerderbereich"],
    //           });
    //         })
    //         .catch(console.error);
    //     },
    //     search() {
    //       if (this.fuse === null) {
    //         this.results = [];
    //         return false;
    //       }

    //       this.results = _(this.fuse.search(this.query))
    //         .orderBy("score", "desc")
    //         .take(3)
    //         .map((r) => r.item)
    //         .values();
    //     },
    //     search: "",

    //     myCourses: getjsonmap(datajson),
    //     get filteredEmployees() {
    //     if (this.search === "") {
    //         return this.myCourses;
    //     }
    //     const filteredItem = this.myCourses.filter((item) => {
    //         return (
    //             // item.title
    //             item.title
    //             // + item.summary
    //             // + (item.recetas[0] ? item.recetas[0].title:'')
    //             // + (item.recetas[1] ? item.recetas[1].title:'')
    //             // + (item.recetas[2] ? item.recetas[2].title:'')
    //             // + (item.recetas[3] ? item.recetas[3].title:'')
    //             // + (item.recetas[4] ? item.recetas[4].title:'')
    //         )
    //             .toLowerCase()
    //             .includes(this.search.toLowerCase())
    //     })

    //     // console.log(data1);
    //    afterFilter(filteredItem);
    //     // global.after = filteredItem;

    //     // test(filteredItem);
    //     return filteredItem;
    //     }
    // };


}

// import the book recommendations module
// const { data1 } = require('../map');
// console.log(data1());
// console.log(books.data1);

// var people = [];
// // Create 100 fake people objects
// for (var i = 0; i < 200; i++) {
//   people.push({
//     firstName: faker.name.firstName(),
//     lastName: faker.name.lastName(),
//     age: faker.random.number({ min: 5, max: 50 }),
//   });
// }
// // Create an array of alphabetical characters
// var alphabet = [];
// for (var i = 0; i < 26; i++) {
//   alphabet.push(String.fromCharCode(65 + i));
// }

// document.addEventListener('alpine:init', () => {
//   Alpine.data('letterFilter', () => ({
//     searchMessage: 'Search here',
//     searchAge: null,
//     tabIndex: null,
//     alphabet,
//     people: people,
//     filterByAge() {
//       return this.people.filter(
//         person => person.age <= parseInt(this.searchAge)
//       );
//     },
//     filterByFirstName() {
//       return this.people.filter(person =>
//         person.firstName.startsWith(this.filteredLetter)
//       );
//     },
//     filteredLetter: null,
//     updateFilter(val) {
//       console.log(this.filteredPeople);
//       this.filteredLetter = val;
//     },
//     get filteredPeople() {
//       console.log(
//         'filterPeople',
//         this.filterByAge().filter(
//           obj => this.filterByFirstName().indexOf(obj) !== -1
//         )
//       );

//       return [...this.filterByFirstName()];
//     },
//     updateTab(val) {
//       this.tabIndex === val ? (this.tabIndex = null) : (this.tabIndex = val);
//     },
//   }));
//   console.log('alpine loaded');
// });