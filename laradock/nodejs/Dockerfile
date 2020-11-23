FROM node:12.18.3

RUN apt-get update && apt-get install -y libglu1 && npm i -g gulp@4.0.2

WORKDIR /var/www

CMD /bin/bash -c "tail -f /dev/null"
