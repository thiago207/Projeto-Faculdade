# 📊 Análise de Preços de Combustíveis no Brasil

Projeto de análise exploratória de dados desenvolvido em **Python**, focado em identificar padrões de volatilidade e distribuição geográfica de preços de combustíveis no mercado brasileiro (2004-2021). O sistema realiza:

* **Coleta e preparação de dados** reais do mercado brasileiro de combustíveis.
* **Limpeza e tratamento** de inconsistências, valores ausentes e outliers.
* **Análise exploratória completa** com estatística descritiva e correlações.
* **Visualizações profissionais** incluindo mapas de calor, heatmaps e gráficos comparativos.
* Tudo desenvolvido de forma autônoma para prática e consolidação de competências em Análise de Dados.

---

## 🎯 Funcionalidades

* 🔍 **Análise geográfica** - Mapeamento de preços por estado e região brasileira.
* 📊 **Comparação de produtos** - Análise de preços de 9 tipos diferentes de combustíveis.
* 📈 **Estudo de volatilidade** - Identificação de padrões de variação de preços por região.
* ⚖️ **Análise de viabilidade** - Comparação entre etanol e gasolina por estado.
* 🗺️ **Visualizações geográficas** - Mapas de calor mostrando distribuição nacional de preços.
* 📉 **Dashboards analíticos** - Métricas agregadas de distribuição e concentração regional.

---

## 🛠️ Tecnologias

* **Python 3.8+**
* **Pandas** — manipulação e transformação de dados
* **Matplotlib** — criação de visualizações profissionais
* **NumPy** — operações estatísticas e numéricas
* **Jupyter Notebook** — ambiente de desenvolvimento e documentação interativa

---

## 📊 Dataset

**Fonte:** Kaggle - Histórico de Preços de Combustíveis (ANP)  
**Período:** 2004 - 2021  
**Cobertura:** Todos os estados brasileiros, 5 regiões geográficas  
**Produtos analisados:** Gasolina Comum, Gasolina Aditivada, Etanol Hidratado, Óleo Diesel, Óleo Diesel S10, GLP, GNV, entre outros

---

## 📈 Principais Análises

### 1. **Distribuição Geográfica de Preços**
Mapa de calor mostrando disparidade regional, com Acre apresentando preços 37% superiores ao Paraná (R$ 3,27/L vs R$ 2,38/L).

### 2. **Análise de Preços por Produto e Região**
Heatmap identificando GLP como produto mais caro (R$ 44,86 - R$ 51,33), enquanto GNV mantém-se mais econômico (R$ 1,92 - R$ 2,18/L).

### 3. **Dashboard de Métricas de Mercado**
Visualização da concentração de produtos (Gasolina Comum, GLP e Etanol representam 58% do mercado) e distribuição regional (Nordeste com 34,5% das operações).

### 4. **Viabilidade Econômica: Etanol vs Gasolina**
Análise de custo-benefício mostrando que etanol só é vantajoso em 4 estados (MS, SP, GO, PR), com razão de preço abaixo de 0,70.

### 5. **Correlação Volatilidade x Preço**
Estudo revelando que região mais cara (Norte, R$ 2,96/L) não é a mais volátil - Sudeste apresenta maior instabilidade (0,051) com menor preço (R$ 2,56/L).

---

## 🔧 Metodologia

```
Coleta de Dados (Kaggle)
         ↓
Exploração Inicial
         ↓
Limpeza e Tratamento
  • Valores ausentes
  • Outliers
  • Padronização
         ↓
Análise Exploratória (EDA)
  • Estatística descritiva
  • Agregações e agrupamentos
  • Correlações
         ↓
Visualização de Insights
  • Mapas de calor
  • Heatmaps
  • Gráficos comparativos
         ↓
Documentação
```

---

## 💡 Principais Insights

✅ **Disparidade regional de 37%** entre estados mais caros (Acre) e mais baratos (Paraná)  
✅ **GLP como outlier** - Preço 15x superior aos demais combustíveis  
✅ **Concentração no Nordeste** - 34,5% das operações comerciais  
✅ **Etanol competitivo em apenas 4 estados** - Maioria favorece gasolina  
✅ **Volatilidade independe de preço** - Regiões caras podem ter estabilidade  


## 🎓 Competências Desenvolvidas

### Técnicas:
* Manipulação avançada de DataFrames (groupby, pivot, merge, agregações)
* Limpeza e tratamento de dados inconsistentes e ausentes
* Criação de visualizações complexas (dual-axis, heatmaps, mapas geográficos)
* Aplicação de estatística descritiva e análise de correlação

### Analíticas:
* Pensamento crítico na interpretação de dados
* Extração de insights acionáveis de datasets complexos
* Storytelling com dados através de visualizações
* Comunicação técnica clara e objetiva

---

## 📁 Estrutura do Projeto

```
📦 analise-combustiveis-brasil/
├── 📊 data/
│   └── dataset_combustiveis.csv
├── 📓 notebooks/
│   ├── 01_exploracao_inicial.ipynb
│   ├── 02_limpeza_dados.ipynb
│   └── 03_analise_visualizacao.ipynb
├── 📈 visualizacoes/
│   ├── mapa_precos_brasil.png
│   ├── heatmap_produto_regiao.png
│   ├── dashboard_analises.png
│   ├── viabilidade_etanol_gasolina.png
│   └── volatilidade_preco_regiao.png
├── 📄 README.md
└── 📄 requirements.txt
```

---

## 📝 Próximos Passos

- [ ] Implementar análise de séries temporais para identificar tendências
- [ ] Desenvolver modelo preditivo de preços com Machine Learning
- [ ] Criar dashboard interativo com Streamlit ou Plotly Dash
- [ ] Adicionar análise de sazonalidade e padrões mensais
- [ ] Integrar dados econômicos externos (inflação, câmbio, petróleo)

---

## 👤 Autor

**[THIAGO FELIPE]**

- GitHub: (https://github.com/thiago207)
- LinkedIn: (https://www.linkedin.com/in/thiago-felipe-ribeiro-brito-48201834a/)
- Email: britoff02@gmail.com
