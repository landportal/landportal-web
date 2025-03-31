// alert("testpt");

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
  .select("#body-pt")
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
      name: "Administração de terras",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8814",
          name: "Agrimensura",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3860",
              name: "Cartografia do uso da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8796",
              name: "Modelo conceitual de administração do território",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8799",
                  name: "Unidades básicas administrativas",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7439",
                  name: "Limite",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8801",
                      name: "Face limite",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8854",
                      name: "Sequência de face limite",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8848",
                  name: "Ponto",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8844",
                  name: "Unidades espaciais",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/8841",
                      name: "Nível",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8866",
                      name: "Parcelas",
                      size: 1.0,
                    },
                  ],
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8845",
                  name: "unidades espaciais baseadas em superfície",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3859",
              name: "Cartografia da ocupação do solo",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8859",
          name: "Documentação da terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8803",
              name: "anotações do imóvel",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8788",
              name: "Fonte (administração de terras)",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8856",
                  name: "Fonte espacial",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3777",
              name: "Título de propriedade",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3837",
          name: "Registro de terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3689",
              name: "Administração cadastral",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7448",
              name: "Registro de escritura",
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
              name: "Titulação de propriedade de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3778",
              name: "Sistema Torrens",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3830",
          name: "Sistema de informação de terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3795",
              name: "Cadastro",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8865",
                  name: "Mapas cadastrales",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3750",
                  name: "Plano de parcela",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3831",
              name: "Sistema de informação geográfica",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3769",
                  name: "Escala (cartografia)",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8849",
              name: "mapas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8846",
              name: "registro de imóveis",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3690",
                  name: "Cadastro predial",
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
      name: "Atores da terra",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/7211",
          name: "Activistas em direitos fundiários",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8804",
          name: "Atividades",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1239",
              name: "Advocacia",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1262",
              name: "Atribuição de autorização legal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3794",
              name: "Avaliação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1244",
              name: "Desenvolvimento das capacidades",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1267",
              name: "Investigação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8820",
              name: "Vigilância",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1253",
          name: "Agricultor",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3775",
              name: "Arrendatário (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8791",
              name: "Pequenos agricultores",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3840",
          name: "Arrendatário",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3839",
          name: "Donos de terras",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8821",
          name: "Especialistas em terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3808",
              name: "Agência de desenvolvimento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3812",
              name: "Associação de agricultores",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3746",
              name: "Notário",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7437",
              name: "Organizações de base comunitária",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3774",
              name: "Geómetra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8798",
          name: "Família",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1271",
          name: "Mulher",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8789",
          name: "Governo",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8809",
          name: "Governo da casa",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1259",
          name: "Pessoas indígenas",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7332",
          name: "Juventude",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3739",
          name: "Comunidade local",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7438",
          name: "População alóctone",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7458",
          name: "Ocupantes",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1264",
          name: "Pecuários",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3868",
          name: "População rural",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3869",
          name: "População urbana",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8802",
          name: "Setor privado",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1252",
              name: "Indústrias extrativas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7216",
              name: "Indústrias de óleo de palma",
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
      name: "Direito fundiário",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3785",
          name: "Acesso à terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3783",
              name: "Despedício (abuso)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3765",
              name: "Direito do primeiro ocupante",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3740",
              name: "Direitos de gestão",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3782",
              name: "Direitos de uso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3701",
              name: "Direitos de controlo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3764",
              name: "Direito de desmatamento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3857",
              name: "Direito de acesso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7440",
              name: "Usufruto",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3725",
          name: "Conflitos de terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3726",
              name: "Disputa por terra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3718",
                  name: "Perito independente",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7446",
                  name: "Conflitos de terra ou resolução de litígios",
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
          name: "Assentamentos humanos",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7271",
              name: "Assentamentos informais",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1261",
              name: "Agricultor sem terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6893",
              name: "Deslocação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7300",
              name: "Desapropriação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3710",
              name: "Despejo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8819",
              name: "Migração",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3747",
              name: "Ocupação",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3717",
                  name: "Ocupação ilegal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8811",
              name: "Reassentamento",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8793",
          name: "Contratos de terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3804",
              name: "Agricultura contratual",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7436",
              name: "Arrendamento",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3711",
                  name: "Arrendamento agrícola",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3742",
              name: "Contrato de abastecimento de estrume",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3715",
              name: "Contratos de pastagem",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3846",
          name: "Posse da terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7447",
              name: "Continuidade no direito à terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3854",
              name: "Direito relativo à água",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3716",
              name: "Direitos à terra de pastagem",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3749",
              name: "Direitos dos órfãos à terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7041",
              name: "Direitos comunitários sobre a terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7465",
              name: "Direito à habitação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3695",
              name: "Direitos de uso comum (terras)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3767",
              name: "Direitos ribeirinhos ou ripários",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3703",
              name: "Direito consuetudinário sobre a terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3752",
              name: "Direitos de pastagem",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8860",
              name: "Acordos de posse de grupos",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3696",
                  name: "Bens comuns",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7245",
              name: "Direitos das terras indígenas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7256",
              name: "Propriedade da terra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3697",
                  name: "Propriedade comunitária",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3702",
                  name: "Direitos de co-propriedade",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3865",
                  name: "Propriedade privada",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3866",
                  name: "Propriedade pública",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3797",
                  name: "Propriedade coletiva",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7467",
              name: "Regularização do arrendamento rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3736",
              name: "Sistemas de posse da terra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3748",
                  name: "Acesso livre (terra)",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3721",
                  name: "Posse de terra indígena",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8851",
                  name: "Regimes informais de posse",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8858",
                  name: "arrendamento fundiário",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7449",
                  name: "Posse formal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7459",
                  name: "Posse legal",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7451",
                  name: "Propriedade plena e livre de encargos",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/7444",
                  name: "Posse consuetudinária",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/8812",
                  name: "Regimes de posse religiosa",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3855",
              name: "Direito do arrendatário",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8863",
              name: "Insegurança de posse",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7213",
              name: "Segurança de posse",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8828",
                  name: "Percepção de segurança de posse",
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
          name: "Imóvel",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3856",
              name: "Direito de propriedade",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3741",
                  name: "direitos de propriedade conjugal",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3720",
              name: "Propriedade informal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8836",
              name: "Propriedade individual da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1247",
              name: "Propriedade comum",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3772",
              name: "Propriedade do estado",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3771",
              name: "Servidão",
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
      name: "Governança da terra",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3838",
          name: "Ordenamento do território",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8847",
              name: "Unidades de construção",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8792",
              name: "Classificação da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3827",
              name: "Distribuição da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3864",
              name: "Planeamento rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3848",
              name: "Ordenamento urbano",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8853",
              name: "Perfil",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7454",
              name: "Reajustamento fundiário",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8852",
              name: "Rede de utilidade",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3853",
              name: "Zonagem",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7254",
          name: "Corrupção",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3806",
          name: "Descentralização",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3807",
          name: "Desenvolvimento",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3700",
              name: "Conflito de interesses",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1268",
              name: "Desenvolvimento rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7210",
              name: "Desenvolvimento sustentável",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7225",
              name: "Pobreza",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8827",
              name: "Cenários pós-conflitos",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3707",
          name: "Domínio eminente",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3803",
              name: "Expropriação",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8824",
          name: "Estado de direito",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3693",
              name: "Codificação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3798",
              name: "Direito comum",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3805",
              name: "Direito consuetudinário",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7466",
              name: "Lei islâmica",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7457",
              name: "Pluralismo jurídico",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3773",
              name: "Lei estatutária",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1255",
          name: "Segurança alimentar",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8842",
              name: "Fome",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3732",
          name: "Direito fundiário",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3789",
              name: "Estrutura agrária",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3787",
                  name: "Legislação agrária",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3730",
              name: "Direitos sobre terra por herança",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3712",
              name: "Legislação florestal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3788",
              name: "Política agrária",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3759",
              name: "Direito imobiliário",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8810",
          name: "Participação pública",
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3836",
          name: "Reforma fundiária",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/1240",
              name: "Reforma agrária",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3744",
              name: "Reforma negociado da posse da terra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3849",
          name: "Urbanização",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8831",
              name: "Direito à cidade",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8797",
              name: "Infra-estrutura",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8861",
              name: "Melhoramento de favelas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8843",
              name: "Habitação social",
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
      name: "Administração fundiária",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3825",
          name: "Cobertura do solo",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8837",
              name: "Mudança na cobertura do solo",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8806",
              name: "Terra de pastagem",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8833",
              name: "Pantanal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3843",
              name: "Terras de pastagem",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7301",
              name: "Terra florestal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8834",
              name: "Terra urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8855",
              name: "Terras secas",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/7443",
          name: "Área de terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8795",
              name: "Terra arável",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3870",
              name: "Área verde urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8838",
              name: "Área periurbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7331",
              name: "Bairro de lata",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8825",
              name: "Terra abandonada",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8794",
              name: "Terra estéril",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3781",
              name: "Terras não reclamadas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3751",
              name: "Terras pastoris",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7442",
              name: "Terras comunitárias",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3698",
                  name: "Território comunitário",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3776",
              name: "Território",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7046",
              name: "Terra agrícola",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3719",
              name: "Terras indígenas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3796",
              name: "Zona costeira",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3871",
              name: "Zona protegida",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3873",
              name: "Zona rural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3874",
              name: "Zona urbana",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1256",
          name: "Actividade florestal",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3845",
              name: "Floresta do estado",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3862",
              name: "Desflorestação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3813",
              name: "Conservação da floresta",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3847",
              name: "Arborização urbana",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3758",
              name: "Florestas privadas",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3801",
              name: "Silvicultura comunitária",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/1251",
          name: "Ambiente",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7223",
              name: "Catástrofe natural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1245",
              name: "Mudança de clima",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1250",
              name: "Desertificação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3826",
              name: "Degradação de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8790",
              name: "Resiliência",
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
          name: "Recurso natural",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8832",
              name: "Água",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3850",
                  name: "Gestão da água",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8829",
              name: "Conflitos",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/1263",
              name: "Gestão de recursos naturais",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/8807",
                  name: "Biodiversidade",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7307",
              name: "Terra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/6487",
          name: "Uso da terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3685",
              name: "Gestão de solos agrícolas",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/7246",
                  name: "Sistema de exploração agrícola",
                  children: [
                    {
                      uri: "https://landportal.org/taxonomy/term/3842",
                      name: "Agricultura suburbana",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3686",
                      name: "Sistema agropastoril",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/8818",
                      name: "Gado",
                      size: 1.0,
                    },
                    {
                      uri: "https://landportal.org/taxonomy/term/3790",
                      name: "Sistemas agroflorestais",
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
              name: "Mudança no uso da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3832",
              name: "Emparcelamento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8808",
              name: "Conservação de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3824",
              name: "Limpeza do terreno",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3814",
              name: "Planos de ordenamento florestal",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7287",
              name: "Indústria mineira",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3822",
              name: "Melhoramento fundiário",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3863",
              name: "Ordenamento sustentável da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3799",
              name: "Pastoreio",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8816",
              name: "Produtividade da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8857",
              name: "Restauração de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3872",
              name: "Reserva natural",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7455",
              name: "Loteamento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7460",
              name: "Uso sustentável da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3810",
              name: "Utilização intensiva da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3823",
              name: "Aptidão da terra",
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
      name: "Mercado fundiário",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/3723",
          name: "Aquisições de terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7462",
              name: "Aquisição compulsória",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3688",
              name: "Atribuição (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3699",
              name: "Concessão (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3706",
              name: "Dote (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3687",
              name: "Alienação (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7461",
              name: "Fideicomisso",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3714",
              name: "Compras governamentais",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3802",
              name: "Indemnização",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3705",
                  name: "Pedido de indemnização",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3708",
              name: "Invasão",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7452",
              name: "Alocação de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3841",
              name: "Linhagem",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3743",
                  name: "Matrilinear",
                  size: 1.0,
                },
                {
                  uri: "https://landportal.org/taxonomy/term/3753",
                  name: "Patrilinear",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3756",
              name: "Prescrição",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3761",
              name: "Regularização da ocupação ilegal",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3731",
          name: "Investimentos em terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3722",
              name: "Fundos de investimento",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3709",
              name: "Gestão da propriedade (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8800",
              name: "Investimento estrangeiro",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/6894",
              name: "Negócios de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3728",
              name: "Apropriação de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8835",
              name: "investimento responsável",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8840",
          name: "Financiamento baseado em terras",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8813",
              name: "captura de valor da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3694",
              name: "Garantia (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3821",
              name: "Imposto sobre propriedade da terra",
              children: [
                {
                  uri: "https://landportal.org/taxonomy/term/3820",
                  name: "Imposto sucessório",
                  size: 1.0,
                },
              ],
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3733",
              name: "Empréstimo de terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3734",
              name: "Renda da terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7456",
              name: "Avaliação de terras",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/3829",
          name: "Economia da terra",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/7445",
              name: "Adjudicação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3691",
              name: "Valor do capital (terra)",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3766",
              name: "Direito de preferência",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8174",
              name: "Entrega de terras",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3735",
              name: "Especulação fundiária",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7463",
              name: "Transações de terra",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3780",
              name: "Custos de transação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/3861",
              name: "Transferência da terra (jurídica)",
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
      name: "equidade fundiária",
      children: [
        {
          uri: "https://landportal.org/taxonomy/term/8862",
          name: "Equidade de gênero",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/3763",
              name: "Direitos no divórcio",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/7450",
              name: "Equidade de género no acesso à terra",
              size: 1.0,
            },
          ],
          size: 1.0,
        },
        {
          uri: "https://landportal.org/taxonomy/term/8826",
          name: "Proteção das minorias",
          children: [
            {
              uri: "https://landportal.org/taxonomy/term/8805",
              name: "Discriminação",
              size: 1.0,
            },
            {
              uri: "https://landportal.org/taxonomy/term/8850",
              name: "Direitos iguais",
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
