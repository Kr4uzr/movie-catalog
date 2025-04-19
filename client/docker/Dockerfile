FROM node:23.11.0

# Define o diretório de trabalho no container
WORKDIR /app

# Copia apenas os arquivos necessários para instalar dependências
COPY package*.json ./

# Instala as dependências
RUN npm install

# Porta usada pela aplicação
EXPOSE 5173

# Comando de inicialização
CMD ["npm", "run", "dev", "--", "--host"]