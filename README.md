# EnergySmartMonitor_TFG

## Instal·lació del servidor

### 1.Instal·lació dels paquets i les seves dependències

Instal·lació git i curl:
```
sudo apt update
sudo apt-get install curl
sudo apt-get install git
```


Instal·lació Docker, si vols la guia d'instal·lació oficial, pots fer clic [aquí](https://docs.docker.com/engine/install/ubuntu/):
```
sudo apt update
sudo apt install apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"
sudo apt update
apt-cache policy docker-ce
sudo apt install docker-ce
```


Instal·lació Docker-Compose, si vols la guia d'instal·lació oficial, pots fer clic [aquí](https://docs.docker.com/compose/install/):
```
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 2.Configuració dels serveis i descàrrega de les dades:

Creació del directori on es desaran els arxius i les dades del projecte, per exemple es pot crear a la home de l'usuari. En aquest cas es crea un directori amb anomenat "**EnergySmartMonitor**":
```
mkdir EnergySmartMonitor
```

Clonem el repositori de GitHub i el desem al directori que s'ha creat en el pas anterior. En aquest cas el directori "**EnergySmartMonitor**":
```
git clone https://github.com/AlexAlaminosUAB/EnergySmartMonitor_TFG.git EnergySmartMonitor/
```

Accedim al directori en el qual s'ha clonat el repositori del pas anterior. En aquest cas el directori "**EnergySmartMonitor**":
```
cd EnergySmartMonitor
```


### 3.Descarrega i arrencada dels contenidors Docker:

A continuació es descarregaran les 3 imatges de Docker i una vegada descarregades i desplegades, s'arrancaran de forma automàtica tots els serveis. Per fer-ho haurem d'executar la següent comanda des del directori "**EnergySmartMonitor**":
```
sudo docker-compose up -d
```
