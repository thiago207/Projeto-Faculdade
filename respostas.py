import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import numpy as np

# Configurações de visualização
sns.set_style("whitegrid")
plt.rcParams['figure.figsize'] = (12, 6)

# Ler dados
df = pd.read_table('2004-2021.tsv')

# Remover colunas de data problemáticas
df = df.drop(['DATA INICIAL', 'DATA FINAL'], axis=1)

# Converter colunas de preços
cols_dist = [
    'PREÇO MÉDIO DISTRIBUIÇÃO',
    'DESVIO PADRÃO DISTRIBUIÇÃO',
    'PREÇO MÍNIMO DISTRIBUIÇÃO',
    'PREÇO MÁXIMO DISTRIBUIÇÃO',
    'COEF DE VARIAÇÃO DISTRIBUIÇÃO',
    'MARGEM MÉDIA REVENDA'
]

for col in cols_dist:
    df[col] = df[col].replace('-', None)
    df[col] = df[col].astype(str).str.replace(',', '.', regex=False)
    df[col] = pd.to_numeric(df[col], errors='coerce')

# Converter colunas de revenda
cols_revenda = [
    'PREÇO MÉDIO REVENDA',
    'DESVIO PADRÃO REVENDA',
    'PREÇO MÍNIMO REVENDA',
    'PREÇO MÁXIMO REVENDA',
    'MARGEM MÉDIA REVENDA',
    'COEF DE VARIAÇÃO REVENDA'
]

for col in cols_revenda:
    if col in df.columns:
        df[col] = df[col].astype(str).str.replace(',', '.', regex=False)
        df[col] = pd.to_numeric(df[col], errors='coerce')

print("="*80)
print("📊 ANÁLISE DE PREÇOS DE COMBUSTÍVEIS (2004-2021)")
print("="*80)

# 1. Visão Geral
print("\n1️⃣ VISÃO GERAL DO DATASET")
print(f"   Total de registros: {len(df):,}")
print(f"   Produtos analisados: {df['PRODUTO'].nunique()}")
print(f"   Estados cobertos: {df['ESTADO'].nunique()}")
print(f"   Regiões: {df['REGIÃO'].nunique()}")

# 2. Distribuição por Produto
print("\n2️⃣ REGISTROS POR TIPO DE COMBUSTÍVEL")
print(df['PRODUTO'].value_counts())

# 3. Análise de Preços por Produto
print("\n3️⃣ PREÇOS MÉDIOS POR PRODUTO (Revenda)")
precos_produto = df.groupby('PRODUTO')['PREÇO MÉDIO REVENDA'].agg(['mean', 'std', 'min', 'max'])
precos_produto.columns = ['Média', 'Desvio Padrão', 'Mínimo', 'Máximo']
print(precos_produto.round(3))

# 4. Análise por Região
print("\n4️⃣ PREÇO MÉDIO DE REVENDA POR REGIÃO")
precos_regiao = df.groupby('REGIÃO')['PREÇO MÉDIO REVENDA'].mean().sort_values(ascending=False)
print(precos_regiao.round(3))

# 5. Margem Média por Região
print("\n5️⃣ MARGEM MÉDIA DE REVENDA POR REGIÃO")
margem_regiao = df.groupby('REGIÃO')['MARGEM MÉDIA REVENDA'].mean().sort_values(ascending=False)
print(margem_regiao.round(3))

# 6. Top 10 Estados Mais Caros
print("\n6️⃣ TOP 10 ESTADOS COM COMBUSTÍVEL MAIS CARO")
estados_caros = df.groupby('ESTADO')['PREÇO MÉDIO REVENDA'].mean().sort_values(ascending=False).head(10)
print(estados_caros.round(3))

# 7. Análise de Variabilidade
print("\n7️⃣ ESTADOS COM MAIOR VARIAÇÃO DE PREÇOS")
variacao_estados = df.groupby('ESTADO')['COEF DE VARIAÇÃO REVENDA'].mean().sort_values(ascending=False).head(10)
print(variacao_estados.round(3))

# VISUALIZAÇÕES
fig, axes = plt.subplots(2, 2, figsize=(16, 12))

# Gráfico 1: Preço Médio por Produto
ax1 = axes[0, 0]
precos_produto_plot = df.groupby('PRODUTO')['PREÇO MÉDIO REVENDA'].mean().sort_values()
precos_produto_plot.plot(kind='barh', ax=ax1, color='steelblue')
ax1.set_title('Preço Médio de Revenda por Produto', fontsize=14, fontweight='bold')
ax1.set_xlabel('Preço (R$/L)', fontsize=12)
ax1.set_ylabel('')

# Gráfico 2: Preço por Região
ax2 = axes[0, 1]
precos_regiao.plot(kind='bar', ax=ax2, color='coral')
ax2.set_title('Preço Médio por Região', fontsize=14, fontweight='bold')
ax2.set_xlabel('')
ax2.set_ylabel('Preço (R$/L)', fontsize=12)
ax2.tick_params(axis='x', rotation=45)

# Gráfico 3: Margem por Região
ax3 = axes[1, 0]
margem_regiao.plot(kind='bar', ax=ax3, color='lightgreen')
ax3.set_title('Margem Média de Revenda por Região', fontsize=14, fontweight='bold')
ax3.set_xlabel('')
ax3.set_ylabel('Margem (R$/L)', fontsize=12)
ax3.tick_params(axis='x', rotation=45)

# Gráfico 4: Top 10 Estados Mais Caros
ax4 = axes[1, 1]
estados_caros.plot(kind='barh', ax=ax4, color='salmon')
ax4.set_title('Top 10 Estados Mais Caros', fontsize=14, fontweight='bold')
ax4.set_xlabel('Preço (R$/L)', fontsize=12)
ax4.set_ylabel('')

plt.tight_layout()
plt.savefig('analise_combustiveis.png', dpi=300, bbox_inches='tight')
plt.show()

# Análise adicional: Gasolina vs Etanol
print("\n8️⃣ COMPARAÇÃO GASOLINA x ETANOL")
if 'GASOLINA COMUM' in df['PRODUTO'].values and 'ETANOL HIDRATADO' in df['PRODUTO'].values:
    gasolina = df[df['PRODUTO'] == 'GASOLINA COMUM']['PREÇO MÉDIO REVENDA'].mean()
    etanol = df[df['PRODUTO'] == 'ETANOL HIDRATADO']['PREÇO MÉDIO REVENDA'].mean()
    
    print(f"   Gasolina Comum: R$ {gasolina:.3f}/L")
    print(f"   Etanol Hidratado: R$ {etanol:.3f}/L")
    print(f"   Razão Etanol/Gasolina: {(etanol/gasolina):.2%}")
    print(f"   {'✅ Etanol vale a pena!' if (etanol/gasolina) < 0.70 else '❌ Gasolina é mais vantajosa'}")

print("\n" + "="*80)
print("✅ Análise concluída! Gráficos salvos em 'analise_combustiveis.png'")
print("="*80)