pipeline {
    //loadbalancer sample
    //Set the Environemnt for Docker Build
    environment {
        registry = "sereneqmo/portfolio" //My Dockerhub repo
        registryCredential = 'sereneqmo-dockerhub' //My pw to dockerhub - credential on Jenkins
        sshCredentials = 'd575df88-c35c-42dc-a8bd-b4bc5bb45196' //My EC2 sshkey
        dockerImageBuildNumber = ''
        dockerImageLatest = ''
    }
    agent {
        label 'master'
    }
    stages {
        stage('Clone repo') {
            steps {
                git url: 'https://github.com/sereneqmo/portfolio-docker2.git'
                //My github repo for all the files
            }
        }
        //docker build . sereneqmo/portfolio
        stage('Building image') {
            steps {
                script {
                    dockerImageBuildNumber = docker.build registry + ":$BUILD_NUMBER"
                    dockerImageLatest = docker.build registry + ":latest"
                }
            }
        }
        //docker push
        stage('Pushing image') {
            steps {
                script {
                    docker.withRegistry( '', registryCredential ) {
                        dockerImageBuildNumber.push()
                        dockerImageLatest.push()
                    }
                }
            }
        }
        //docker rm sereneqmo/portfolio
        stage('Cleaning environment') {
            steps {
                script {
                    sh "docker rmi $registry:$BUILD_NUMBER"
                    sh "docker rmi $registry:latest"
                }
            }
        }
        //shell into ec2 instance(no host key checking as Jenkins doesn't have memory)
        //docker stop portfolio
        //docker rm portfolio
        //docker image rm portfolio
        //docker pull sereneqmo/portfolio - pull the most updated image
        //docker run -p 80:80 sereneqmo/portfolio (-d:docker container will start as a daemonized container --name portfolio)
        stage('Deploy to Server') {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: sshCredentials, keyFileVariable: 'sshkey')]){
                    script {
                        sh """
                            ssh -i ${sshkey} -o 'StrictHostKeyChecking=no' ec2-user@ec2-54-201-83-103.us-west-2.compute.amazonaws.com \
                            ' \
                            docker stop portfolio; \
                            docker rm portfolio; \
                            docker image rm sereneqmo/portfolio; \
                            docker pull sereneqmo/portfolio; \
                            docker run -d -p 80:80 --name portfolio sereneqmo/portfolio \
                            ' \
                        """
                    }
                }
            }
            
        }
    }
}
