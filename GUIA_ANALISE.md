# 🎯 Guia de Análise Exploratória - Combustíveis Brasil

Roteiro completo para análise de dados de combustíveis (2004-2021)

---

## 📋 ETAPA 1: LIMPEZA E PREPARAÇÃO DOS DADOS

**O que fazer:**
- [ ] Remover as colunas de data problemáticas
- [ ] Converter todas as colunas de preços (substituir vírgula por ponto)
- [ ] Verificar e tratar valores nulos
- [ ] Checar se há duplicatas
- [ ] Validar se os tipos de dados estão corretos

**Perguntas para responder:**
- Quantos registros você tem no total?
- Quantos valores nulos existem em cada coluna importante?
- Todos os estados/regiões estão presentes?

---

## 📊 ETAPA 2: ANÁLISE EXPLORATÓRIA INICIAL

**O que investigar:**

### 2.1 Visão Geral
- Quantos produtos diferentes existem?
- Quantas regiões e estados estão no dataset?
- Qual o período coberto (nome do arquivo sugere 2004-2021)?
- Quantos postos foram pesquisados (média, min, max)?

### 2.2 Distribuição dos Dados
- Qual produto tem mais registros?
- Qual região/estado tem mais observações?
- Há desbalanceamento nos dados?

**Gráficos sugeridos:**
- Contagem de registros por produto (barras)
- Distribuição de registros por região (pizza ou barras)
- Número de postos pesquisados por estado (mapa de calor ou barras)

---

## 💰 ETAPA 3: ANÁLISE DE PREÇOS

### 3.1 Análise por Produto
**Perguntas:**
- Qual combustível é mais caro em média?
- Qual tem maior variação de preço?
- Como os preços se distribuem (histograma)?

**Gráficos:**
- Boxplot de preços por produto
- Histograma de distribuição de preços
- Barras horizontais com média de preço

### 3.2 Análise Geográfica
**Perguntas:**
- Qual região tem o combustível mais caro?
- Quais os 5 estados mais caros? E os 5 mais baratos?
- Há diferença significativa entre regiões?

**Gráficos:**
- Mapa de calor (estados x preço médio)
- Barras por região
- Gráfico de dispersão (latitude/longitude se tiver)

### 3.3 Comparação Distribuição x Revenda
**Perguntas:**
- Qual a diferença média entre preço de distribuição e revenda?
- Essa margem varia por região?
- Essa margem varia por produto?

**Gráficos:**
- Gráfico de barras agrupadas (distribuição vs revenda)
- Scatterplot (preço distribuição x preço revenda)
- Heatmap de margem por estado/produto

---

## 🔍 ETAPA 4: ANÁLISES ESPECÍFICAS

### 4.1 Gasolina vs Etanol
**Análise crucial:**
- Calcular a razão Etanol/Gasolina por estado
- Identificar onde o etanol compensa (< 70%)
- Comparar com o preço médio nacional

**Gráficos:**
- Mapa mostrando onde etanol vale a pena
- Linha de tendência da razão
- Barras comparativas

### 4.2 Variabilidade de Preços
**Perguntas:**
- Onde os preços são mais instáveis?
- Qual produto tem maior volatilidade?
- O coeficiente de variação está relacionado com a região?

**Gráficos:**
- Boxplot do coeficiente de variação
- Barras dos estados com maior/menor variação
- Correlação entre variáveis

### 4.3 Análise de Margem
**Perguntas:**
- Qual região tem maior margem de lucro?
- A margem varia por tipo de combustível?
- Há outliers (margens muito altas/baixas)?

**Gráficos:**
- Violinplot de margem por região
- Scatterplot (preço x margem)
- Heatmap estado x margem média

---

## 📈 ETAPA 5: INSIGHTS AVANÇADOS

### 5.1 Correlações
**O que analisar:**
- Correlação entre todas as variáveis numéricas
- Preço está correlacionado com número de postos?
- Margem está correlacionada com variação?

**Gráficos:**
- Matriz de correlação (heatmap)
- Pairplot das principais variáveis

### 5.2 Agrupamentos
**Análise:**
- Agrupar estados por perfil de preço (K-means?)
- Identificar padrões regionais
- Classificar produtos por volatilidade

### 5.3 Outliers e Anomalias
**Investigar:**
- Há preços absurdos (muito altos/baixos)?
- Quais registros são atípicos?
- Por que esses outliers existem?

---

## 🎨 ETAPA 6: VISUALIZAÇÕES PARA LINKEDIN/GITHUB

### Para o LinkedIn (impacto visual):
1. **Mapa do Brasil** colorido por preço médio
2. **Infográfico** com números-chave (preço médio nacional, estado mais caro/barato)
3. **Gráfico comparativo** Gasolina x Etanol bem visual
4. **Dashboard resumo** com 4 gráficos principais

### Para o GitHub (técnico):
1. **README completo** com metodologia
2. **Notebooks bem comentados** com cada etapa
3. **Gráficos estáticos** salvos em pasta /img
4. **Tabela de resultados** principais
5. **Requirements.txt** com dependências

---

## 📝 ETAPA 7: STORYTELLING - CONCLUSÕES

**Estruture suas conclusões respondendo:**

### Perguntas Econômicas:
- Onde o brasileiro paga mais caro pelo combustível?
- Vale a pena usar etanol? Em quais estados?
- Qual a margem de lucro média dos postos?

### Perguntas Geográficas:
- Há desigualdade regional nos preços?
- Por que algumas regiões são mais caras?
- Estados produtores têm preços menores?

### Perguntas de Mercado:
- Onde há maior concorrência (menor variação)?
- Onde o mercado é mais instável?
- Qual produto tem margem maior?

---

## 🛠️ FERRAMENTAS E TÉCNICAS SUGERIDAS

### Bibliotecas:
- `pandas` - manipulação de dados
- `matplotlib` / `seaborn` - gráficos
- `plotly` - gráficos interativos (opcional)
- `geopandas` - mapas (avançado)

### Técnicas:
- `.groupby()` - agregar por categoria
- `.pivot_table()` - tabelas dinâmicas
- `.corr()` - matriz de correlação
- `.describe()` - estatísticas descritivas

---

## 📌 CHECKLIST DO PROJETO

**Básico:**
- [ ] Limpeza de dados documentada
- [ ] Pelo menos 5 gráficos diferentes
- [ ] Análise descritiva completa
- [ ] 3-5 insights principais

**Intermediário:**
- [ ] Análise de correlações
- [ ] Comparações regionais detalhadas
- [ ] Identificação de outliers
- [ ] Gráficos profissionais e bem formatados

**Avançado:**
- [ ] Mapa geográfico do Brasil
- [ ] Análise de clustering
- [ ] Dashboard interativo
- [ ] Previsões ou modelagem

---

## 💡 DICAS FINAIS

1. **Comece simples**: análises básicas primeiro, depois avança
2. **Documente tudo**: comente seu código, explique suas escolhas
3. **Conte uma história**: conecte os gráficos com narrativa
4. **Seja crítico**: questione os dados, procure explicações
5. **Pense no público**: LinkedIn = visual, GitHub = técnico

---

## 📚 RECURSOS ÚTEIS

### Documentação:
- [Pandas Documentation](https://pandas.pydata.org/docs/)
- [Seaborn Gallery](https://seaborn.pydata.org/examples/index.html)
- [Matplotlib Tutorials](https://matplotlib.org/stable/tutorials/index.html)

### Inspiração:
- Kaggle Notebooks sobre análise exploratória
- GitHub repositórios de ciência de dados
- Medium posts sobre EDA (Exploratory Data Analysis)

---

**Boa sorte! 🚀 Qualquer dúvida sobre COMO fazer algo (não a resposta), é só perguntar!**

---

*Criado para projeto de análise de combustíveis no Brasil (2004-2021)*