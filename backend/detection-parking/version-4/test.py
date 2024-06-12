from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
import requests
import time

# Configurar opciones para Chrome en modo headless
chrome_options = Options()
chrome_options.add_argument("--headless")
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Ruta al ChromeDriver
chromedriver_path = '/usr/local/bin/chromedriver'  # Actualiza esta línea con tu ruta

# Crear una instancia del navegador
service = Service(chromedriver_path)
driver = webdriver.Chrome(service=service, options=chrome_options)

try:
    # Abrir la página
    driver.get('http://192.168.1.255:4747')

    # Esperar un momento para asegurarse de que la página esté completamente cargada
    time.sleep(5)

    # Descargar la imagen
    url = 'http://192.168.1.255:4747/cam/1/frame.jpg'
    response = requests.get(url)

    if response.status_code == 200:
        with open('frame.jpg', 'wb') as file:
            file.write(response.content)
        print("Imagen descargada y guardada como 'frame.jpg'")
    else:
        print(f"Error al descargar la imagen: {response.status_code}")

finally:
    driver.quit()
