// alert("test");
// (function ($) {
//   $(document).ready(function () {
//     // jQuery code targeting #body-body
//     // $("#body-en").css("background-color", "lightblue");
//   });
// })(jQuery);

var m = [20, 120, 20, 120],
  w = 1280 - m[1] - m[3],
  h = 800 - m[0] - m[2],
  i = 0,
  root;

var tree = d3.layout.tree().size([h, w]);

var diagonal = d3.svg.diagonal().projection(function (d) {
  return [d.y, d.x];
});

var vis = d3
  .select("#body-en")
  .append("svg:svg")
  .attr("width", w + m[1] + m[3])
  .attr("height", h + m[0] + m[2])
  .append("svg:g")
  .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

var dataset = {
  uri: "http://landvoc.org",
  name: "LandVoc",
  children: [
    {
      uri: "http://landportal.org/taxonomy/term/3858",
      name: "land administration",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/8859",
          name: "land documentation",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8803",
              name: "land records",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8788",
              name: "sources (land administration)",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/8856",
                  name: "spatial source",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3777",
              name: "title deeds",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3830",
          name: "land information systems",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3795",
              name: "cadastres",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/8865",
                  name: "cadastral maps",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3750",
                  name: "parcel plan",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3831",
              name: "geographical information systems",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3769",
                  name: "scale",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8846",
              name: "land register",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3690",
                  name: "cadastral register",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8849",
              name: "maps",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3837",
          name: "land registration",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3689",
              name: "cadastral administration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7448",
              name: "deed registration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/6891",
              name: "land titling",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8839",
              name: "sporadic registration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8830",
              name: "systematic registration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3778",
              name: "Torrens system",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8814",
          name: "land surveying",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8796",
              name: "Land Administration Domain Model",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/8799",
                  name: "basic administrative unit",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/7439",
                  name: "boundaries",
                  children: [
                    {
                      uri: "http://landportal.org/taxonomy/term/8801",
                      name: "boundary face",
                      size: 1.0,
                    },
                    {
                      uri: "http://landportal.org/taxonomy/term/8854",
                      name: "boundary face string",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8845",
                  name: "face based spatial units",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8848",
                  name: "points",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8844",
                  name: "spatial units",
                  children: [
                    {
                      uri: "http://landportal.org/taxonomy/term/8841",
                      name: "levels",
                      size: 1.0,
                    },
                    {
                      uri: "http://landportal.org/taxonomy/term/8866",
                      name: "parcels",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3859",
              name: "land cover mapping",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3860",
              name: "land use mapping",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/8822",
      name: "land equity",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/8862",
          name: "gender equity",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/7450",
              name: "gender equity in access to land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3763",
              name: "rights in divorce",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8826",
          name: "protection of minorities",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8805",
              name: "discrimination",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8850",
              name: "equal rights",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/6107",
      name: "land governance",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/7254",
          name: "corruption",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3806",
          name: "decentralization",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3807",
          name: "development",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3700",
              name: "conflict of interest",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8827",
              name: "post-conflict settings",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7225",
              name: "poverty",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1268",
              name: "rural development",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7210",
              name: "sustainable development",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3707",
          name: "eminent domain",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3803",
              name: "expropriation",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1255",
          name: "food security",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8842",
              name: "hunger",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3732",
          name: "land law",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3789",
              name: "agrarian structure",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3787",
                  name: "agricultural and rural legislation",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3712",
              name: "forest law",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3730",
              name: "land inheritance rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3788",
              name: "land policies",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3759",
              name: "property law",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3836",
          name: "land reform",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/1240",
              name: "agrarian reform",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3744",
              name: "negotiated land reform",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3838",
          name: "land use planning",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8847",
              name: "building units",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8792",
              name: "land classification",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3827",
              name: "land distribution",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7454",
              name: "land readjustment",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8853",
              name: "profiles",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3864",
              name: "rural planning",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3848",
              name: "urban planning",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8852",
              name: "utility networks",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3853",
              name: "zoning",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8810",
          name: "public participation",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8824",
          name: "rule of law",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3693",
              name: "codification",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3798",
              name: "common law",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3805",
              name: "customary law",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7466",
              name: "Islamic law",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7457",
              name: "legal pluralism",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3773",
              name: "statute law",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3849",
          name: "urbanization",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8797",
              name: "infrastructure",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8831",
              name: "rights to the city",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8861",
              name: "slum upgrading",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8843",
              name: "social housing",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/3834",
      name: "land management",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/1251",
          name: "environment",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/1245",
              name: "climate change",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1250",
              name: "desertification",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3826",
              name: "land degradation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7223",
              name: "natural disasters",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8790",
              name: "resilience",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1254",
          name: "fisheries",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1256",
          name: "forestry",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3801",
              name: "community forestry",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3862",
              name: "deforestation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3813",
              name: "forest conservation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3758",
              name: "private forests",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3845",
              name: "state forests",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3847",
              name: "urban forestry",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7443",
          name: "land area",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8825",
              name: "abandoned land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8795",
              name: "arable land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3796",
              name: "coastal area",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7442",
              name: "common lands",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3698",
                  name: "communal territory",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7046",
              name: "farmland",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3870",
              name: "green belts",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3719",
              name: "indigenous lands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3751",
              name: "pastoral lands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8838",
              name: "periurban areas",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3871",
              name: "protected areas",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3873",
              name: "rural areas",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7331",
              name: "slums",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3776",
              name: "territory",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3781",
              name: "unclaimed lands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3874",
              name: "urban areas",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8794",
              name: "waste land",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3825",
          name: "land cover",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8855",
              name: "drylands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7301",
              name: "forest land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8806",
              name: "grasslands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8837",
              name: "land cover change",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3843",
              name: "rangelands",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8834",
              name: "urban land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8833",
              name: "wetlands",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/6487",
          name: "land use",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3685",
              name: "agricultural land management",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/7246",
                  name: "farming systems",
                  children: [
                    {
                      uri: "http://landportal.org/taxonomy/term/3790",
                      name: "agroforestry systems",
                      size: 1.0,
                    },
                    {
                      uri: "http://landportal.org/taxonomy/term/3686",
                      name: "agropastoral systems",
                      size: 1.0,
                    },
                    {
                      uri: "http://landportal.org/taxonomy/term/8818",
                      name: "livestock",
                      size: 1.0,
                    },
                    {
                      uri: "http://landportal.org/taxonomy/term/3842",
                      name: "peri-urban agriculture",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1241",
              name: "agriculture",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3814",
              name: "forest management plans",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3799",
              name: "grazing",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3810",
              name: "intensive land use",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3824",
              name: "land clearing",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8808",
              name: "land conservation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3832",
              name: "land consolidation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3822",
              name: "land improvement",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8816",
              name: "land productivity",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8857",
              name: "land restoration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7455",
              name: "land subdivision",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3823",
              name: "land suitability",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8864",
              name: "land use change",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7287",
              name: "mining",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3872",
              name: "nature reserves",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3863",
              name: "sustainable land management",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7460",
              name: "sustainable land use",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8823",
          name: "natural resources",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8829",
              name: "conflicts",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7307",
              name: "land",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1263",
              name: "natural resources management",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/8807",
                  name: "biodiversity",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8832",
              name: "water",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3850",
                  name: "water management",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/3835",
      name: "land markets",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/3723",
          name: "land acquisitions",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3687",
              name: "alienation (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3688",
              name: "assignment (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3802",
              name: "compensation",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3705",
                  name: "disturbance",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7462",
              name: "compulsory acquisitions",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3699",
              name: "concession (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3706",
              name: "dowry (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3708",
              name: "encroachment",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3714",
              name: "government purchases",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7452",
              name: "land allocation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3841",
              name: "lineage",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3743",
                  name: "matrilineal",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3753",
                  name: "patrilineal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3756",
              name: "prescription",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3761",
              name: "regularization of illegal occupation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7461",
              name: "trust",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8840",
          name: "land-based financing",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3694",
              name: "collateral (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3733",
              name: "land loans",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3734",
              name: "land rent",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3821",
              name: "land tax",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3820",
                  name: "inheritance tax",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7456",
              name: "land valuation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8813",
              name: "land value capture",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3829",
          name: "land economics",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/7445",
              name: "adjudication",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3691",
              name: "capital value (land)",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8174",
              name: "land delivery",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3735",
              name: "land speculation",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7463",
              name: "land transactions",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3861",
              name: "land transfers",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3766",
              name: "pre-emption rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3780",
              name: "transaction costs",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3731",
          name: "land investments",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3709",
              name: "estate management",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8800",
              name: "foreign investment",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3722",
              name: "investment funds",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/6894",
              name: "land deals",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3728",
              name: "land grabbing",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8835",
              name: "responsible investment",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/1260",
      name: "land rights",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/8815",
          name: "human settlements",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/6893",
              name: "displacement",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7300",
              name: "dispossession",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3710",
              name: "eviction",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7271",
              name: "informal settlements",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1261",
              name: "landlessness",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8819",
              name: "migration",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3747",
              name: "occupation",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3717",
                  name: "illegal occupation",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8811",
              name: "resettlement",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3785",
          name: "land access",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3701",
              name: "control rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3740",
              name: "management rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3857",
              name: "right of access",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3765",
              name: "right of first occupancy",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3764",
              name: "rights of clearance",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3782",
              name: "use rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7440",
              name: "usufruct",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3783",
              name: "waste (abuse)",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3725",
          name: "land conflicts",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3726",
              name: "land dispute",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3718",
                  name: "independent experts",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/7446",
                  name: "land dispute resolution",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8793",
          name: "land contracts",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3804",
              name: "contract farming",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3715",
              name: "grazing contracts",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7436",
              name: "leases",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3711",
                  name: "farm tenancy",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3742",
              name: "manure contract",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3846",
          name: "land tenure",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3695",
              name: "common rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7041",
              name: "community land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7447",
              name: "continuum of land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3703",
              name: "customary land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3716",
              name: "grazing land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8860",
              name: "group tenure arrangements",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3696",
                  name: "commons",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7465",
              name: "housing rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7245",
              name: "indigenous land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7256",
              name: "land ownership",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3797",
                  name: "collective ownership",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3697",
                  name: "communal ownership",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3702",
                  name: "co-ownership rights",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3865",
                  name: "private ownership",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3866",
                  name: "public ownership",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3736",
              name: "land tenure systems",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/7444",
                  name: "customary tenure",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/7449",
                  name: "formal tenure",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/7451",
                  name: "freehold",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3721",
                  name: "indigenous tenure",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8851",
                  name: "informal tenure regimes",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8858",
                  name: "leaseholds",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/3748",
                  name: "open access (land)",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/8812",
                  name: "religious tenure regimes",
                  size: 1.0,
                },
                {
                  uri: "http://landportal.org/taxonomy/term/7459",
                  name: "statutory tenure",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3749",
              name: "orphans land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3752",
              name: "pastoral land rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3767",
              name: "riparian rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3855",
              name: "tenant's rights",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8863",
              name: "tenure insecurity",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7467",
              name: "tenure regularization",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7213",
              name: "tenure security",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/8828",
                  name: "perception of tenure security",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3854",
              name: "water rights",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7441",
          name: "property",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/1247",
              name: "common property",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3720",
              name: "informal property",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8836",
              name: "private property",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3856",
              name: "property rights",
              children: [
                {
                  uri: "http://landportal.org/taxonomy/term/3741",
                  name: "marital property rights",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3771",
              name: "servient tenement",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3772",
              name: "state property",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "http://landportal.org/taxonomy/term/8817",
      name: "land stakeholders",
      children: [
        {
          uri: "http://landportal.org/taxonomy/term/8804",
          name: "activities",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/1239",
              name: "advocacy",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3794",
              name: "assessment",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1244",
              name: "capacity building",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1262",
              name: "legal empowerment",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/8820",
              name: "monitoring",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/1267",
              name: "research",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8798",
          name: "families",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1253",
          name: "farmers",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/8791",
              name: "smallholders",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3775",
              name: "tenant farmers",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8789",
          name: "government",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8809",
          name: "households",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1259",
          name: "indigenous peoples",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3839",
          name: "landowners",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8821",
          name: "land practitioners",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/3808",
              name: "development agencies",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3812",
              name: "farmers associations",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7437",
              name: "grassroots organizations",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3746",
              name: "notary",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/3774",
              name: "surveyors",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7211",
          name: "land rights activists",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3739",
          name: "local communities",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7438",
          name: "non indigenous peoples",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1264",
          name: "pastoralists",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/8802",
          name: "private sector",
          children: [
            {
              uri: "http://landportal.org/taxonomy/term/1252",
              name: "extractive industries",
              size: 1.0,
            },
            {
              uri: "http://landportal.org/taxonomy/term/7216",
              name: "palm oil industries",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3868",
          name: "rural population",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7458",
          name: "squatters",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3840",
          name: "tenants",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/3869",
          name: "urban population",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/1271",
          name: "women",
          size: 1.0,
        },
        {
          uri: "http://landportal.org/taxonomy/term/7332",
          name: "youth",
          size: 1.0,
        },
      ],
      size: 1.0,
    },
  ],
  size: 1.0,
};

root = dataset;
root.x0 = h / 2;
root.y0 = 0;

function toggleAll(d) {
  if (d.children) {
    d.children.forEach(toggleAll);
    toggle(d);
  }
}

// Initialize the display to show a few nodes.
root.children.forEach(toggleAll);
update(root);
// });

function update(source) {
  var duration = d3.event && d3.event.altKey ? 5000 : 500;

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse();

  // Normalize for fixed-depth.
  nodes.forEach(function (d) {
    d.y = d.depth * 180;
  });

  // Update the nodes…
  var node = vis.selectAll("g.node").data(nodes, function (d) {
    return d.id || (d.id = ++i);
  });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node
    .enter()
    .append("svg:g")
    .attr("class", "node")
    .attr("transform", function (d) {
      return "translate(" + source.y0 + "," + source.x0 + ")";
    })
    .on("click", function (d) {
      toggle(d);
      update(d);
    });

  nodeEnter
    .append("svg:circle")
    .attr("r", 1e-6)
    .style("fill", function (d) {
      return d._children ? "lightsteelblue" : "#fff";
    });

  // add a link to the concept
  var a = nodeEnter
    .append("a")
    .attr("xlink:href", function (d) {
      return d.uri;
    })
    .attr("target", "_blank");

  a.append("svg:text")
    .attr("x", function (d) {
      return d.children || d._children ? -10 : 10;
    })
    .attr("dy", ".35em")
    .attr("text-anchor", function (d) {
      return d.children || d._children ? "end" : "start";
    })
    .text(function (d) {
      return d.name != null ? d.name : d.uri;
    })
    .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node
    .transition()
    .duration(duration)
    .attr("transform", function (d) {
      return "translate(" + d.y + "," + d.x + ")";
    });

  nodeUpdate
    .select("circle")
    .attr("r", 6)
    .style("fill", function (d) {
      return d._children ? "lightsteelblue" : "#fff";
    });

  nodeUpdate.select("text").style("fill-opacity", 1);

  // Transition exiting nodes to the parent's new position.
  var nodeExit = node
    .exit()
    .transition()
    .duration(duration)
    .attr("transform", function (d) {
      return "translate(" + source.y + "," + source.x + ")";
    })
    .remove();

  nodeExit.select("circle").attr("r", 1e-6);

  nodeExit.select("text").style("fill-opacity", 1e-6);

  // Update the links…
  var link = vis.selectAll("path.link").data(tree.links(nodes), function (d) {
    return d.target.id;
  });

  // Enter any new links at the parent's previous position.
  link
    .enter()
    .insert("svg:path", "g")
    .attr("class", "link")
    .attr("d", function (d) {
      var o = {
        x: source.x0,
        y: source.y0,
      };
      return diagonal({
        source: o,
        target: o,
      });
    })
    .transition()
    .duration(duration)
    .attr("d", diagonal);

  // Transition links to their new position.
  link.transition().duration(duration).attr("d", diagonal);

  // Transition exiting nodes to the parent's new position.
  link
    .exit()
    .transition()
    .duration(duration)
    .attr("d", function (d) {
      var o = {
        x: source.x,
        y: source.y,
      };
      return diagonal({
        source: o,
        target: o,
      });
    })
    .remove();

  // Stash the old positions for transition.
  nodes.forEach(function (d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}

// Toggle children.
function toggle(d) {
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
}

(function ($) {
  $(document).ready(function () {
    $("text").mouseover(function () {
      $(this).attr("class", "ext-link");
    });
    $("text").mouseout(function () {
      $(this).attr("class", "");
    });
  });
})(jQuery);

// $(document).ready(function () {
//   // add external link behavior to every external link
//   $("text").mouseover(function () {
//     $(this).attr("class", "ext-link");
//   });
//   $("text").mouseout(function () {
//     $(this).attr("class", "");
//   });

// $("#help-popover").popover({
//   html: true,
//   trigger: "click",
//   delay: {
//     show: 0,
//     hide: 400,
//   },
//   content:
//     "<ol><li>Click a blue circle to unfold the tree (white circles are the tree leaves and are inactive).</li><li>Click an open node to fold id.</li><li>Click a label to navigate to this concept URI.</li></ol>",
//   placement: "bottom",
// });
// $("#help-popover")
//   .css("text-decoration", "underline")
//   .css("cursor", "pointer");
// });
