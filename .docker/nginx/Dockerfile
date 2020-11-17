FROM nginx:1.19

# Install cerboot
#RUN apt-get update && apt-get upgrade -y \
#		add-apt-repository ppa:certbot/certbot \
#		apt install python-certbot-nginx \
#		certbot --nginx -d DOMAIN_NAME \
RUN apt-get update && apt-get install certbot -y && apt install python-certbot-nginx -y
#		apt-get install python-certbot-nginx
#RUN ln -s /snap/bin/certbot /usr/bin/certbot && certbot certonly --nginx

#sudo apt-get update
#sudo apt-get install software-properties-common
#sudo add-apt-repository universe
#sudo add-apt-repository ppa:certbot/certbot
#sudo apt-get update
