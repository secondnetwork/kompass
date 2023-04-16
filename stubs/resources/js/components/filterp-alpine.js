
import * as geodata from '../getjson'
import * as map from '../map'
import Fuse from 'fuse.js'



export default () => {
    window._ = require("lodash");
    return {
        search: "",
        results: "",
        pageNumber: 0,
        size: 8,
        total: "",
        fuse: null,
        myForData: "",
        fonds: "",
        fonts: "",
        eler: false,
        efre: false,
        esf: false,
        init() {
            let url = new URL(window.location);
            url.pathname = geodata.datajson;


            fetch(url.toString())
                .then((response) => response.json())
                .then((items) => {

                    this.myForData = items;

                    this.fuse = new Fuse(this.myForData, {
                        isCaseSensitive: false,
                        findAllMatches: true,
                        includeMatches: true,
                        includeScore: true,
                        useExtendedSearch: false,
                        threshold: 0.4,
                        location: 0,
                        distance: 2,
                        maxPatternLength: 32,
                        keys: [
                       
                            'title', 'type', 'projektbeschreibung', 'sublime', 'foerderbereich' ,'amtsbezirk'
                        ]
                    });
                })
                .catch(console.error);


        },
        get filteredEmployees() {

     
            var filterdate = []
            // var filterdate = [ 
                
            //         //  {amtsbezirk: 'Braunschweig'},{amtsbezirk: 'Lüneburg'},
        
            //         //  {amtsbezirk: 'Leine-Weser'},{amtsbezirk: 'Weser-Ems'}
               
                        
            //                 {  type: fontsELER },
            //                 {  type: fontsEFER },
            //             {amtsbezirk: 'Lüneburg'},
            //             {amtsbezirk: 'Weser-Ems'}
                         
                    
                    
            // ]


         
            if (this.fonds == 'eler') {
                filterdate = [ {  type: '=eler' , amtsbezirk: 'Lüneburg'}]
            }
            if (this.fonds == 'efre') {
                filterdate = [ {  type: '=efre' }]
            }
            if (this.fonds == 'esf') {
                filterdate = [ {  type: '=esf' }]
            }




            const start = this.pageNumber * this.size,
                    end = start + this.size;

            if (this.search === "") {

                // if (this.fonts) {
                //     var filterend = (_.filter(this.myForData, this.fonts));
                // } else {
                //     var filterend = this.myForData;
                // }

                // const result = this.fuse.search({
                //     $or: [{ type: 'EFRE' }, { type: 'ESF' }]
                //   }).map((r) => r.item)
                //   .value()

                // this.results = _(this.fuse.search({
                //     $or: [ { type: 'ESF' }]
                // }    
                // ))
                // .map((r) => r.item)
                // .orderBy("score", "desc")
                // .value();
           

                // var filterend = (_.filter(this.myForData, {  'type' : 'EFRE','type' : 'ESF'}));


                var filterend = this.results;


                this.total = filterend.length;
                return filterend.slice(start, end);


            }
      


            this.results = _(this.fuse.search(
              {  $or: filterdate }
            ))
            .map((r) => r.item)
            .value();

            // this.results = _(this.fuse.search(this.search))
            //     .map((r) => r.item)
            //     .orderBy("score", "desc")
            //     .value();

            if (this.fonts) {

                // var filterend = _(this.fuse.search({

                //     $and: [
                //         {
                //           $path: ['title'],
                //           $val: this.search
                //         },
                //         {
                //           $path: ['type'],
                //           $val: this.fonts
                //         }
                //       ]

                  
                //   }))
                // .map((r) => r.item)
                // .orderBy("score", "desc")
                // .value();

                var filterend = (_.filter(this.results, this.fonts));
            } else {
                var filterend = this.results;
            }

            // var filterend = (_.filter(this.results, {  'type' : 'EFRE','type' : 'ESF'}));
            this.total = filterend.length;
            console.log('end');
            console.log(this.results);
            return filterend.slice(start, end)
            //Return the filtered data

        },

        //Create array of all pages (for loop to display page numbers)
        pages() {
            return Array.from({
                length: Math.ceil(this.total / this.size),
            });
        },

        //Next Page
        nextPage() {
            this.pageNumber++;
        },

        //Previous Page
        prevPage() {
            this.pageNumber--;
        },

        //Total number of pages
        pageCount() {
            return Math.ceil(this.total / this.size);
        },

        //Return the start range of the paginated results
        startResults() {
            return this.pageNumber * this.size + 1;
        },

        //Return the end range of the paginated results
        endResults() {
            let resultsOnPage = (this.pageNumber + 1) * this.size;

            if (resultsOnPage <= this.total) {
                return resultsOnPage;
            }

            return this.total;
        },

        //Link to navigate to page
        viewPage(index) {
            this.pageNumber = index;
        },
    };



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