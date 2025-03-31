// alert("testes");
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
  .select("#body-es")
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
      name: "Administración de tierras",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8814",
          name: "Agrimensura",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3860",
              name: "Cartografía del uso de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8796",
              name: "Modelo para el ámbito de la administración del territorio",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8799",
                  name: "Unidades administrativas básicas",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7439",
                  name: "Límite",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8801",
                      name: "Linde",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8854",
                      name: "Cuerda de linde",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8848",
                  name: "Punto",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8844",
                  name: "Unidades espaciales",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8841",
                      name: "Nivel",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8866",
                      name: "Parcelas de tierra",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8845",
                  name: "unidades espaciales basadas en superficie",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3859",
              name: "Cartografía de la cubierta vegetal",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8859",
          name: "Documentación de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8803",
              name: "registros de las relaciones de tenencia",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8788",
              name: "Fuente (administración de tierras)",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8856",
                  name: "Fuente espacial",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3777",
              name: "titulo de propriedad",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3837",
          name: "Registro de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3689",
              name: "administración catastral",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7448",
              name: "Registro de escrituras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8839",
              name: "Registro esporádico",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8830",
              name: "Registro sistemático",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6891",
              name: "Titulación de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3778",
              name: "sistema Torrens",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3830",
          name: "Sistemas de información de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3795",
              name: "Catastros",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8865",
                  name: "Mapas catastrales",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3750",
                  name: "plan parcelario",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3831",
              name: "Sistemas de información geográfica",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3769",
                  name: "escala",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8849",
              name: "Mapa",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8846",
              name: "Registro de propiedad",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3690",
                  name: "registro catastral",
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
      name: "Actores involucrados con la tierra",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/7211",
          name: "Activistas por el derecho a la tierra",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8804",
          name: "Actividades",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1239",
              name: "Incidencia",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1262",
              name: "Empoderamiento legal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3794",
              name: "Evaluación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1244",
              name: "creación de capacidad",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1267",
              name: "Investigación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8820",
              name: "Vigilancia",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1253",
          name: "Agricultores",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3775",
              name: "tenedor",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8791",
              name: "Pequeños agricultores",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3840",
          name: "Arrendatarios",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3839",
          name: "Propietarios de la tierra",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8821",
          name: "Profesionales vinculados a la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3808",
              name: "Agencias de desarrollo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3812",
              name: "Asociaciones de agricultores",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3746",
              name: "notario",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7437",
              name: "organizaciones de base comunitaria",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3774",
              name: "geómetra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8798",
          name: "Familia",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1271",
          name: "Mujeres",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8789",
          name: "Gobierno",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8809",
          name: "Hogares",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1259",
          name: "pueblos indígenas",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7332",
          name: "Juventud",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3739",
          name: "Comunidades locales",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7438",
          name: "foráneo",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7458",
          name: "Ocupantes (ilegales)",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1264",
          name: "Pastoralistas",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3868",
          name: "Población rural",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3869",
          name: "Población urbana",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8802",
          name: "Sector privado",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1252",
              name: "Industrias extractivas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7216",
              name: "Industrias de la palma aceitera",
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
      name: "Derechos de tierras",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3785",
          name: "acceso a la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3783",
              name: "Abuso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3765",
              name: "derecho de primer ocupante",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3740",
              name: "derechos de gestión",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3782",
              name: "derechos de uso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3701",
              name: "derechos de control",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3764",
              name: "derechos de tala",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3857",
              name: "Derecho de uso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7440",
              name: "Usufructo",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3725",
          name: "conflictos por la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3726",
              name: "conflicto agrario",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3718",
                  name: "Expertos Independientes (litigios legales)",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7446",
                  name: "Resolución de disputas por la tierra",
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
          name: "Asentamientos humanos",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7271",
              name: "Asentamientos informales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1261",
              name: "Campesino sin tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6893",
              name: "Desplazamiento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7300",
              name: "Desposesión",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3710",
              name: "desalojo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8819",
              name: "Migración",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3747",
              name: "ocupación",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3717",
                  name: "ocupación ilegal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8811",
              name: "Reasentamiento",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8793",
          name: "Contratos de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3804",
              name: "Agricultura contractual",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7436",
              name: "Arrendamiento",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3711",
                  name: "arrendamiento de fincas",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3742",
              name: "contrato sobre abono natural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3715",
              name: "contratos de pastoreo",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3846",
          name: "Tenencia de la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7447",
              name: "Continuidad en el derecho a la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3854",
              name: "Derecho de aguas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3716",
              name: "derechos de pastoreo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3749",
              name: "derechos sobre la tierra de los huérfanos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7041",
              name: "Derechos sobre tierras comunales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7465",
              name: "Derechos de vivienda",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3695",
              name: "régimen comunal (de tierras)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3767",
              name: "derechos ribereños",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3703",
              name: "derechos tradicionales de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3752",
              name: "derechos agropastoriles",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8860",
              name: "Arreglos grupales sobre la tenencia",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3696",
                  name: "bienes comunes",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7245",
              name: "Derechos sobre tierras indígenas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7256",
              name: "Propiedad de la tierra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3697",
                  name: "propiedad comunal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3702",
                  name: "copropiedad",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3865",
                  name: "Propiedad privada",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3866",
                  name: "Propiedad pública",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3797",
                  name: "Propiedad colectiva",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7467",
              name: "Regularización de la tenencia",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3736",
              name: "modos de aprovechamiento",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3748",
                  name: "libre acceso (espacio)",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3721",
                  name: "tenencia de tierras indígenas",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8851",
                  name: "Regímenes de tenencia informales",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8858",
                  name: "contrato de arrendamiento",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7449",
                  name: "Tenencia formal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7459",
                  name: "Tenencia legal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7451",
                  name: "Propiedad plena y libre",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7444",
                  name: "Tenencia consuetudinaria",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8812",
                  name: "Regímenes de tenencia religiosa",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3855",
              name: "Derecho del propietario",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8863",
              name: "Inseguridad de la tenencia",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7213",
              name: "Seguridad de la tenencia",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8828",
                  name: "Percepción de seguridad de la tenencia",
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
          name: "Posesión",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3856",
              name: "Derechos de propiedad",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3741",
                  name: "derechos de propiedad maritales",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3720",
              name: "propiedad informal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8836",
              name: "Propiedad individual de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1247",
              name: "Propiedad comunal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3772",
              name: "propiedad pública",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3771",
              name: "predio sirviente",
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
      name: "Gobernanza de la tierra",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3838",
          name: "Ordenación territorial",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8847",
              name: "Unidades de edificación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8792",
              name: "Clasificación de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3827",
              name: "Distribución de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3864",
              name: "Planificación rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3848",
              name: "Planificación urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8853",
              name: "Perfil",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7454",
              name: "Reajuste de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8852",
              name: "Red de servicios públicos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3853",
              name: "Zonificación",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7254",
          name: "Corrupción",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3806",
          name: "Descentralización",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3807",
          name: "Desarrollo",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3700",
              name: "conflicto de interés",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1268",
              name: "Desarrollo rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7210",
              name: "Desarrollo sostenible",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7225",
              name: "Pobreza",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8827",
              name: "Escenarios posconflictos",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3707",
          name: "dominio eminente",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3803",
              name: "Expropiación",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8824",
          name: "Estado de derecho",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3693",
              name: "codificación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3798",
              name: "Derecho común",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3805",
              name: "Derecho consuetudinario",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7466",
              name: "Ley islámica",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7457",
              name: "Pluralismo legal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3773",
              name: "derecho escrito",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1255",
          name: "Seguridad alimentaria",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8842",
              name: "Hambre",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3732",
          name: "derecho agrario",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3789",
              name: "Estructura agraria",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3787",
                  name: "Legislación agraria",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3730",
              name: "derechos de herencia sobre la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3712",
              name: "legislación forestal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3788",
              name: "Política agraria",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3759",
              name: "derecho inmobiliario",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8810",
          name: "Participación pública",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3836",
          name: "Reforma de la tenencia de la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1240",
              name: "Reforma agraria",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3744",
              name: "reforma negociada de la tenencia de la tierra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3849",
          name: "Urbanización",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8831",
              name: "Derechos a la ciudad",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8797",
              name: "Infraestructura",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8861",
              name: "Mejoramiento de barriadas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8843",
              name: "Vivienda social",
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
      name: "Ordenación de tierras",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3825",
          name: "cobertura de suelos",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8837",
              name: "Alteración de la cubierta vegetal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8806",
              name: "Praderas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8833",
              name: "Tierras húmedas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3843",
              name: "Tierras de pastos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7301",
              name: "tierras forestales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8834",
              name: "Tierra urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8855",
              name: "Tierras de secano",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7443",
          name: "área de tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8795",
              name: "Tierras arables",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3870",
              name: "Zonas verdes",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8838",
              name: "Áreas periurbanas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7331",
              name: "Suburbio",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8825",
              name: "Tierras abandonadas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8794",
              name: "Eriales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3781",
              name: "baldíos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3751",
              name: "tierras de pastoreo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7442",
              name: "Tierras comunales",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3698",
                  name: "territorio comunal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3776",
              name: "territorio",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7046",
              name: "Tierras agrícolas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3719",
              name: "tierras indígenas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3796",
              name: "Area costera",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3871",
              name: "Zonas protegidas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3873",
              name: "Zonas rurales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3874",
              name: "Zonas urbanas",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1256",
          name: "Ciencias forestales",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3845",
              name: "Bosques estatales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3862",
              name: "Deforestación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3813",
              name: "conservación de montes",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3847",
              name: "arboricultura urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3758",
              name: "bosques privados",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3801",
              name: "silvicultura comunitaria",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1251",
          name: "Medio ambiente",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7223",
              name: "Desastres naturales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1245",
              name: "Cambio climático",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1250",
              name: "Desertificación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3826",
              name: "Degradación de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8790",
              name: "Resiliencia",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1254",
          name: "Pesca",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8823",
          name: "Recursos naturales",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8832",
              name: "Agua",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3850",
                  name: "Ordenación de aguas",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8829",
              name: "Conflictos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1263",
              name: "ordenación de recursos naturales",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8807",
                  name: "Biodiversidad",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7307",
              name: "Tierras",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/6487",
          name: "Utilización de la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3685",
              name: "gestión de tierras agricolas",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/7246",
                  name: "Sistemas de explotación",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/3842",
                      name: "Agricultura peri urbana",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3686",
                      name: "Sistemas agropascícolas",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8818",
                      name: "Ganado",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3790",
                      name: "sistemas agroforestales",
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
              name: "Agricultura",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8864",
              name: "Cambio de uso de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3832",
              name: "Concentración parcelaria",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8808",
              name: "Conservación de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3824",
              name: "Desmonte",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3814",
              name: "planes de ordenación forestal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7287",
              name: "minería",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3822",
              name: "Mejora de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3863",
              name: "Ordenación de tierras sostenible",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3799",
              name: "Pastoreo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8816",
              name: "Productividad de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8857",
              name: "Restauración de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3872",
              name: "Reservas naturales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7455",
              name: "Subdivisión de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7460",
              name: "Uso sostenible de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3810",
              name: "Utilización intensiva de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3823",
              name: "Vocación de la tierra",
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
      name: "Mercado de tierras",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3723",
          name: "adquisiciones de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7462",
              name: "Adquisición obligatoria",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3688",
              name: "cesión del arrendamiento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3699",
              name: "concesión (fundo)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3706",
              name: "dote (tierra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3687",
              name: "enajenación (de la tierra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7461",
              name: "Fideicomiso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3714",
              name: "Compras gubernamentales",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3802",
              name: "Indemnización",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3705",
                  name: "reclamación de indemnización",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3708",
              name: "invasión",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7452",
              name: "Asignación de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3841",
              name: "Linaje",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3743",
                  name: "matrilineal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3753",
                  name: "patrilineal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3756",
              name: "prescripción",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3761",
              name: "regularización de ocupación",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3731",
          name: "inversiones en tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3722",
              name: "Fondos de inversión",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3709",
              name: "gestión de bienes",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8800",
              name: "Inversiones extranjeras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6894",
              name: "Negocios de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3728",
              name: "acaparamiento de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8835",
              name: "Inversión responsable",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8840",
          name: "Financiamiento basado en la transacción de tierras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8813",
              name: "captura del valor de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3694",
              name: "garantía (fundo)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3821",
              name: "Impuesto a la tierra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3820",
                  name: "Impuesto sobre la herencia",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3733",
              name: "préstamos de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3734",
              name: "renta de la tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7456",
              name: "Valoración de tierras",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3829",
          name: "Economía de la tierra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7445",
              name: "Adjudicación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3691",
              name: "valor de capital (tierra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3766",
              name: "derechos de prelación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8174",
              name: "Entrega de tierra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3735",
              name: "Especulación de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7463",
              name: "Transacciones de tierras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3780",
              name: "costos de transacción",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3861",
              name: "Asignación de tierras",
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
      name: "equidad en la tierra",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8862",
          name: "Equidad de género",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3763",
              name: "Derechos en el divorcio",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7450",
              name: "Equidad de género en el acceso a la tierra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8826",
          name: "Protección de las minorías",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8805",
              name: "Discriminación",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8850",
              name: "Igualdad de derechos",
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
