pipeline {
    environment {
        registry = "sereneqmo/portfolio"
        registryCredential = 'sereneqmo-dockerhub'
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
    }
}