# Usa la imagen oficial de PHP
FROM php:8.2-cli

# Copia los archivos del proyecto al contenedor
COPY . /usr/src/app

# Establece el directorio de trabajo
WORKDIR /usr/src/app

# Comando por defecto al iniciar el contenedor
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/usr/src/app"]
