#!groovy

def dockerImageName = 'doc-graviton'
def dockerRegistry = 'https://localhost:5000'
def dockerRepository = 'platform'
def dockerCredentialsId = 'docker'
def buildSlave = 'localhost:5000/evoja/jenkins-build-slave-php7:develop'

node {
    stage('Checkout') {
        checkout scm
    }

    def dockerImageTag = sh(returnStdout: true, script: 'git describe --all').trim().replaceAll(/(.*\/)?(.+)/,'$2')

    stage('Env') {
        echo "*** Show env variables: ***" + \
             "\n dockerRegistry: " + dockerRegistry + \
             "\n dockerRepository: " + dockerRepository + \
             "\n dockerCredentialsId: " + dockerCredentialsId + \
             "\n dockerImageName: " + dockerImageName + \
             "\n dockerImageTag: " + dockerImageTag
    }

    stage('Build & Push') {

        docker.withRegistry(dockerRegistry, dockerCredentialsId) {
            docker.image(buildSlave).inside() {
                sh '''
                    composer install
                    php vendor/bin/sculpin generate --env=prod
                '''
            }

            // Set repository and image name
            def image = docker.build dockerRepository + "/" + dockerImageName, "--pull --build-arg TAG=${dockerImageTag} ."

            // Push actual tag
            image.push(dockerImageTag)

            // Push latest tag if it's a release
            if ((dockerImageTag ==~ /v(\d+.\d+.\d+)/)) {
            	image.push('latest')
            }

            echo "*** Docker image successfully pushed to registry. ***"
        }
    }
}
