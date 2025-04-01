# MineSwap

MineSwap es una aplicación web desarrollada con Laravel que proporciona una plataforma para intercambiar activos de manera segura y eficiente.

## Características

- **Intercambio de Activos:** Permite a los usuarios intercambiar diferentes tipos de activos.
- **Integración con CoinGate y Razorpay:** Soporte para pagos y transacciones mediante las pasarelas de pago CoinGate y Razorpay.
- **Seguridad:** Implementa medidas de seguridad robustas para proteger las transacciones y los datos de los usuarios.

## Instalación

Sigue estos pasos para instalar y configurar el proyecto:

1. Clona el repositorio:
    ```bash
    git clone https://github.com/sharkstar03/mineswap.git
    cd mineswap
    ```

2. Instala las dependencias de PHP usando Composer:
    ```bash
    composer install
    ```

3. Configura el archivo `.env` con tus variables de entorno. Puedes copiar el archivo de ejemplo:
    ```bash
    cp .env.example .env
    ```

4. Genera la clave de la aplicación:
    ```bash
    php artisan key:generate
    ```

5. Configura la base de datos en el archivo `.env` y ejecuta las migraciones:
    ```bash
    php artisan migrate
    ```

6. Instala las dependencias de Node.js y compila los recursos:
    ```bash
    npm install
    npm run dev
    ```

7. Inicia el servidor de desarrollo:
    ```bash
    php artisan serve
    ```

## Uso

### Intercambio de Activos

1. Regístrate o inicia sesión en la plataforma.
2. Navega a la sección de intercambio.
3. Selecciona el tipo de activo que deseas intercambiar.
4. Sigue las instrucciones para completar la transacción.

### Integración con CoinGate

Para realizar pagos mediante CoinGate, asegúrate de tener una cuenta y generar las credenciales API necesarias.

### Integración con Razorpay

Para realizar pagos mediante Razorpay, configura las credenciales API en el archivo `.env`.

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas contribuir al proyecto, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Agrega nueva funcionalidad'`).
4. Empuja la rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un pull request.

## Licencia

Este proyecto está licenciado bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más información.

## Agradecimientos

- **[Laravel](https://laravel.com/):** Framework utilizado para el desarrollo del proyecto.
- **[CoinGate](https://coingate.com/):** Pasarela de pago integrada.
- **[Razorpay](https://razorpay.com/):** Pasarela de pago integrada.
