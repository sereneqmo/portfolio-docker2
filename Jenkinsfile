pipeline {
    environment {
        registry = "sereneqmo/portfolio"
        registryCredential = 'sereneqmo-dockerhub'
        sshCredentials = 'd575df88-c35c-42dc-a8bd-b4bc5bb45196'
        dockerImageBuildNumber = ''
        dockerImageLatest = ''
    }
    agent {
        label 'master'
    }
    stages {
        stage('Clone repo') {
            steps {
                git url: 'https://github.com/sereneqmo/portfolio-docker.git'
            }
        }
        stage('Building image') {
            steps {
                script {
                    dockerImageBuildNumber = docker.build registry + ":$BUILD_NUMBER"
                    dockerImageLatest = docker.build registry + ":latest"
                }
            }
        }
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
        stage('Cleaning environment') {
            steps {
                script {
                    sh "docker rmi $registry:$BUILD_NUMBER"
                    sh "docker rmi $registry:latest"
                }
            }
        }
        stage('Deploy to Server') {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: sshCredentials, keyFileVariable: 'sshkey')]){
                    script {
                        sh """
                            ssh -i ${sshkey} -o 'StrictHostKeyChecking=no' ec2-user@ec2-54-70-27-216.us-west-2.compute.amazonaws.com \
                            ' \
                            docker image rm sereneqmo/portfolio --force; \
                            docker pull sereneqmo/portfolio; \
                            docker run -d -p 80:80 sereneqmo/portfolio \
                            ' \
                        """
                    }
                }
            }
            
        }
    }
}