// alert("testfr");

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
  .select("#body-fr")
  .append("svg:svg")
  .attr("width", w + m[1] + m[3])
  .attr("height", h + m[0] + m[2])
  .append("svg:g")
  .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

var dataset = {
  uri: "https://landvoc.org",
  name: "LandVoc",
  children: [
    {
      uri: "https://landportal.org/taxonomy/term/3858",
      name: "Administration foncière",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8814",
          name: "arpentage",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3860",
              name: "Cartographie de l' utilisation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8796",
              name: "Modèle conceptuel d'administration du territoire",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8799",
                  name: "unité administrative de base",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7439",
                  name: "Délimitation",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8801",
                      name: "faces d'un volume",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8854",
                      name: "arêtes d'un volume",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8848",
                  name: "point",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8844",
                  name: "unité spatiale",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8841",
                      name: "niveau",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8866",
                      name: "parcelle",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8845",
                  name: "unité spatiale basée en superficie",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3859",
              name: "Cartographie de l'occupation du sol",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8859",
          name: "documents cadastraux",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8803",
              name: "registres fonciers",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8788",
              name: "sources (administration foncière)",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8856",
                  name: "Source spatiale",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3777",
              name: "titre foncier",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3837",
          name: "Enregistrement foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3689",
              name: "administration cadastrale",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7448",
              name: "enregistrement de l’acte",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8839",
              name: "enregistrement sporadique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8830",
              name: "enregistrement systématique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6891",
              name: "immatriculation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3778",
              name: "système Torrens",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3830",
          name: "Systèmes d'information foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3795",
              name: "Cadastre",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8865",
                  name: "plans cadrastales",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3750",
                  name: "plan parcellaire",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3831",
              name: "Système d'information géographique",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3769",
                  name: "échelle (cartographie)",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8849",
              name: "Carte",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8846",
              name: "Registre de la propriété",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3690",
                  name: "registre cadastral",
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
      uri: "https://landportal.org/taxonomy/term/8817",
      name: "acteurs du foncier",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/7211",
          name: "militants du droit à la terre",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8804",
          name: "activité",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1239",
              name: "plaidoyer",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1262",
              name: "attribution du pouvoir légal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3794",
              name: "évaluation",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1244",
              name: "développement des capacités",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1267",
              name: "Recherche",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8820",
              name: "surveillance",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1253",
          name: "Agriculteur",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3775",
              name: "tenancier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8791",
              name: "exploitant agricole",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3840",
          name: "locataires",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3839",
          name: "Propriétaire foncier",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8821",
          name: "praticien du foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3808",
              name: "Agence de développement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3812",
              name: "Association d'agriculteurs",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3746",
              name: "notaire",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7437",
              name: "organisation de base",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3774",
              name: "géomètre",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8798",
          name: "Famille",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1271",
          name: "Femme",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8789",
          name: "gouvernement",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8809",
          name: "Ménage",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1259",
          name: "peuple autochtone",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7332",
          name: "Jeunesse",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3739",
          name: "communautés locales",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7438",
          name: "population allochtone",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7458",
          name: "squatteur",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1264",
          name: "éleveurs",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3868",
          name: "Population rurale",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3869",
          name: "Population urbaine",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8802",
          name: "secteur privé",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1252",
              name: "industries extractives",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7216",
              name: "l'industrie de l'huile de palme",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "https://landportal.org/taxonomy/term/1260",
      name: "Droit foncier",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3785",
          name: "accès à la terre",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3783",
              name: "droit de disposer (abus)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3765",
              name: "droit du premier occupant",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3740",
              name: "droit de gestion",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3782",
              name: "droits d'usage",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3701",
              name: "droit de contrôle",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3764",
              name: "droit de la hache",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3857",
              name: "droit d'usage",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7440",
              name: "Usufruit",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3725",
          name: "conflit foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3726",
              name: "dispute foncière",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3718",
                  name: "expert indépendant",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7446",
                  name: "résolution des conflits fonciers",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8815",
          name: "établissements humains",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7271",
              name: "implantations sauvages",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1261",
              name: "Paysannat sans terre",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6893",
              name: "déplacement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7300",
              name: "expropriation",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3710",
              name: "déguerpissement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8819",
              name: "Migration",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3747",
              name: "occupation",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3717",
                  name: "occupation illégale",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8811",
              name: "Réinstallation",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8793",
          name: "contrat foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3804",
              name: "Agriculture contractuelle",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7436",
              name: "Bail",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3711",
                  name: "fermage",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3742",
              name: "contrat de fumure",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3715",
              name: "contrat de pâturage",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3846",
          name: "tenure foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7447",
              name: "continuum des droits fonciers",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3854",
              name: "Réglementation des eaux",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3716",
              name: "droit sur les terres de parcours",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3749",
              name: "droit des orphelins à la terre",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7041",
              name: "droits fonciers collectifs",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7465",
              name: "droit au logement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3695",
              name: "Droit d'utilisation commune",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3767",
              name: "droit du riverain",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3703",
              name: "droit foncier coutumier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3752",
              name: "droit foncier pastoral",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8860",
              name: "régimes fonciers collectifs",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3696",
                  name: "biens communs",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7245",
              name: "droits fonciers des peuples autochtones",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7256",
              name: "Propriété foncière",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3697",
                  name: "communaux",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3702",
                  name: "copropriété",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3865",
                  name: "Propriété privée",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3866",
                  name: "Propriété publique",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3797",
                  name: "Propriété collective",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7467",
              name: "régularisation foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3736",
              name: "systèmes de tenure",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3748",
                  name: "accès libre (territoire)",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3721",
                  name: "tenure foncière indigène",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8851",
                  name: "régime foncier informel",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8858",
                  name: "tenure à bail",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7449",
                  name: "droits fonciers formels",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7459",
                  name: "droits statutaires",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7451",
                  name: "en pleine propriété",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7444",
                  name: "régime coutumier",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8812",
                  name: "régime foncier religieux",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3855",
              name: "Droit du locataire",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8863",
              name: "insécurité foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7213",
              name: "sécurité foncière",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8828",
                  name: "perception de la sécurité foncière",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7441",
          name: "Possession",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3856",
              name: "Droit de propriété",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3741",
                  name: "droits patrilinéaires de la propriété",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3720",
              name: "propriété informelle",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8836",
              name: "Propriété individuelle de la terre",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1247",
              name: "Propriété commune",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3772",
              name: "propriété publique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3771",
              name: "servitude",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "https://landportal.org/taxonomy/term/6107",
      name: "gouvernance foncière",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3838",
          name: "Aménagement du territoire",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8847",
              name: "unité de bâtiments",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8792",
              name: "Classification des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3827",
              name: "Distribution des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3864",
              name: "Aménagement rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3848",
              name: "Aménagement urbain",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8853",
              name: "profil",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7454",
              name: "réaffectation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8852",
              name: "réseau des services collectifs",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3853",
              name: "Zonage",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7254",
          name: "Corruption",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3806",
          name: "Décentralisation",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3807",
          name: "Développement",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3700",
              name: "conflit d'intérêts",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1268",
              name: "Développement rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7210",
              name: "Développement durable",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7225",
              name: "Pauvreté",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8827",
              name: "contexte de sortie de conflit",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3707",
          name: "domaine éminent",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3803",
              name: "Expropriation",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8824",
          name: "État de droit",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3693",
              name: "codification",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3798",
              name: "Droit commun",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3805",
              name: "Droit coutumier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7466",
              name: "loi islamique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7457",
              name: "pluralisme juridique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3773",
              name: "droit écrit de l' état",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1255",
          name: "Sécurité alimentaire",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8842",
              name: "faim",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3732",
          name: "loi foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3789",
              name: "Structure agraire",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3787",
                  name: "Droit agricole et rural",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3730",
              name: "droit d' héritage sur la terre",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3712",
              name: "droit forestier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3788",
              name: "Politique foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3759",
              name: "droit immobilier",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8810",
          name: "Participation publique",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3836",
          name: "Réforme foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1240",
              name: "Réforme agraire",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3744",
              name: "réforme agraire négociée",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3849",
          name: "Urbanisation",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8831",
              name: "droit à la ville",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8797",
              name: "infrastructure",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8861",
              name: "rénovation des quartiers de taudis",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8843",
              name: "logement social",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "https://landportal.org/taxonomy/term/3834",
      name: "Gestion foncière",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3825",
          name: "couverture du sol",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8837",
              name: "Changement de couvert végétal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8806",
              name: "Herbage",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8833",
              name: "terre humide",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3843",
              name: "Parcours",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7301",
              name: "terrain forestier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8834",
              name: "Terrains urbains",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8855",
              name: "terres arides",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7443",
          name: "espace foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8795",
              name: "Terre arable",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3870",
              name: "Espace vert",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8838",
              name: "zone périurbaine",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7331",
              name: "taudis",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8825",
              name: "Terre abandonnée",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8794",
              name: "Terre inculte",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3781",
              name: "terres vacantes",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3751",
              name: "espace pastoral",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7442",
              name: "Terre communale",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3698",
                  name: "territoire communal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3776",
              name: "territoire",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7046",
              name: "Terre agricole",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3719",
              name: "territoire indigène",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3796",
              name: "Zone côtière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3871",
              name: "Zone protegée",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3873",
              name: "Zone rurale",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3874",
              name: "Zone urbaine",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1256",
          name: "Foresterie",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3845",
              name: "Forêt domaniale",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3862",
              name: "Déboisement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3813",
              name: "conservation des forêts",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3847",
              name: "foresterie urbaine",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3758",
              name: "forêts privées",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3801",
              name: "foresterie communautaire",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1251",
          name: "Environnement",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7223",
              name: "Catastrophe naturelle",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1245",
              name: "Changement climatique",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1250",
              name: "Désertification",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3826",
              name: "Dégradation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8790",
              name: "Résilience",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1254",
          name: "Pêches",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8823",
          name: "Ressource naturelle",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8832",
              name: "eau",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3850",
                  name: "Gestion des eaux",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8829",
              name: "conflits",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1263",
              name: "gestion des ressources naturelles",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8807",
                  name: "Biodiversité",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7307",
              name: "Terre",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/6487",
          name: "Utilisation des terres",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3685",
              name: "gestion de terre agricole",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/7246",
                  name: "Système d'exploitation agricole",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/3842",
                      name: "Agriculture périurbaine",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3686",
                      name: "Système agropastoral",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8818",
                      name: "Bétail",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3790",
                      name: "systèmes agroforestiers",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1241",
              name: "Agriculture",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8864",
              name: "changement d'affectation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3832",
              name: "Remembrement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8808",
              name: "conservation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3824",
              name: "Défrichement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3814",
              name: "plan d'aménagement forestier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7287",
              name: "industrie minière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3822",
              name: "Amélioration foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3863",
              name: "Gestion foncière durable",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3799",
              name: "Pâturage",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8816",
              name: "Productivité des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8857",
              name: "remise en état des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3872",
              name: "Réserve naturelle",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7455",
              name: "allocation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7460",
              name: "utilisation durable des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3810",
              name: "Utilisation intensive des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3823",
              name: "Aptitude des terres",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "https://landportal.org/taxonomy/term/3835",
      name: "Marché foncier",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3723",
          name: "acquisition foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7462",
              name: "acquisition obligatoire",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3688",
              name: "transfert de droits (tenure foncière)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3699",
              name: "concession (foncière)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3706",
              name: "dot (terre)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3687",
              name: "aliénation (terrain)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7461",
              name: "ressources financières",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3714",
              name: "achat gouvernemental",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3802",
              name: "Indemnisation",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3705",
                  name: "demande de compensation",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3708",
              name: "empiétement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7452",
              name: "allocation des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3841",
              name: "Lignée",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3743",
                  name: "matrilinéaire",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3753",
                  name: "patrilinéaire",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3756",
              name: "prescription",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3761",
              name: "régularisation d' occupation",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3731",
          name: "investissement foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3722",
              name: "fonds d'investissement",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3709",
              name: "gestion de biens (fonciers)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8800",
              name: "investissement étranger",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6894",
              name: "acquisition des terres",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3728",
              name: "accaparement foncier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8835",
              name: "investissement responsable",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8840",
          name: "financement du foncier",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8813",
              name: "captation de la plus-value foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3694",
              name: "collatéral (bien foncier)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3821",
              name: "Impôt foncier",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3820",
                  name: "Impôt sur succession",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3733",
              name: "prêt foncier",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3734",
              name: "rente foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7456",
              name: "évaluation foncière",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3829",
          name: "Économie foncière",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7445",
              name: "arbitrage",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3691",
              name: "valeur du bien (foncier)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3766",
              name: "droit de préemption",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8174",
              name: "production foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3735",
              name: "spéculation foncière",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7463",
              name: "transactions foncières",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3780",
              name: "coût de transaction",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3861",
              name: "Mutation foncière",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
      ],
      size: 1.0,
    },
    {
      uri: "https://landportal.org/taxonomy/term/8822",
      name: "valeur nette du terrain",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8862",
          name: "Équité entre les sexes",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3763",
              name: "droits lors du divorce",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7450",
              name: "la parité hommes-femmes en matière d’accès à la terre",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8826",
          name: "protection des minorités",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8805",
              name: "discrimination",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8850",
              name: "egalité des droits",
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
};
// console.log(dataset.uri);
//var json = JSON.parse(dataset);
// d3.json("json?language=&root=", function(json) {
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
      var o = { x: source.x0, y: source.y0 };
      return diagonal({ source: o, target: o });
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
      var o = { x: source.x, y: source.y };
      return diagonal({ source: o, target: o });
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

// $(document).ready(function () {
//   // add external link behavior to every external link
//   $("text").mouseover(function () {
//     $(this).attr("class", "ext-link");
//   });
//   $("text").mouseout(function () {
//     $(this).attr("class", "");
//   });

//   $("#help-popover").popover({
//     html: true,
//     trigger: "click",
//     delay: { show: 0, hide: 400 },
//     content:
//       "<ol><li>Click a blue circle to unfold the tree (white circles are the tree leaves and are inactive).</li><li>Click an open node to fold id.</li><li>Click a label to navigate to this concept URI.</li></ol>",
//     placement: "bottom",
//   });
//   $("#help-popover")
//     .css("text-decoration", "underline")
//     .css("cursor", "pointer");
// });

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
