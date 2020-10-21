<template>
    <div id="container">
        <span id="error" v-if="errorMessage">
            {{ errorMessage }}
        </span>

        <div class="poll-header">
            <h1>Cria sua enquente!</h1>
            <button @click="toggleModalForm" class="new-poll">Nova Enquete</button>
        </div>
        <div v-if="polls.length !== 0">
            <div class="poll" v-for="poll in polls" :key="poll.id">

                <p>
                    {{ poll.poll_description }}
                </p>

                <div class="poll-options">
                    <button class="view-poll" @click="showView(poll.id)">Visualizar</button>
                    <button class="stats-poll" @click="showStats(poll.id)">Estatísticas</button>
                </div>

            </div>
        </div>
        <div v-else>
            <p>Sem questão cadastradas!</p>
        </div>

        <div v-if="isOpenModalForm" class="modal-container">
            <div class="modal">
                <form @submit.prevent="createPoll" id="poll-form">
                    <label>
                        Descrição:
                        <input type="text" v-model="description" placeholder="Digite a descrição.">
                    </label>

                    <div>
                        <label>Opções:</label>
                        <input type="text" v-model="options[0]" placeholder="Digite a primeira opção.">
                        <input type="text" v-model="options[1]" placeholder="Digite a segunda opção.">
                        <input type="text" v-model="options[2]" placeholder="Digite a terceira opção.">
                    </div>
                </form>
                <div class="buttons">
                    <button @click="toggleModalForm" type="button">Cancelar</button>
                    <button form="poll-form" type="submit">Criar</button>
                </div>
            </div>
        </div>


        <div v-if="isOpenModalView" class="modal-container">
            <div class="modal">

                <h2>{{ pollView.poll_description }}</h2>

                <ul>
                    <li v-for="(option, index) in pollView.options" :key="index">
                        {{ option.option_description }}
                        <button @click="pollVote(option.option_id)">Votar</button>
                    </li>
                </ul>

                <button @click="toggleModalView" type="button">Cancelar</button>
            </div>
        </div>

        <div v-if="isOpenModalStats" class="modal-container">
            <div class="modal">
                <h2>Opções</h2>
                <ul v-if="pollStats.stats.votes">
                    <li v-for="(vote, index) in pollStats.stats.votes" :key="index">
                        {{ getOptions(vote.option_id) }} | Qtd de votos: {{ vote.qty }}
                    </li>
                </ul>
                <p v-else>Nenhuma das opções foram votadas.</p>
                <span>Visualizações: {{pollStats.stats.views}}</span>
                <button @click="toggleModalStats" type="button">Fecha</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PollContainer",
    data() {
        return {
            polls: [],
            errorMessage: '',
            isOpenModalForm: false,
            isOpenModalView: false,
            isOpenModalStats: false,
            description: '',
            options: [],
            pollView: {},
            pollStats: {
                poll: {},
                stats: {}
            },
        }
    },

    created() {
        this.fetchPolls();
    },

    methods: {
        fetchPolls() {
            axios.get('api/poll')
            .then(response => this.polls = response.data)
            .catch(err => this.errorMessage = 'Não foi possível carregar os dados.');
        },

        createPoll() {
            axios.post('api/poll', {
                'poll_description': this.description,
                'options': this.options
            }).then(response => console.log(response))
                .catch(err => this.errorMessage = 'Não foi possível carregar os dados.');
            this.description = '';
            this.options = [];
            this.fetchPolls();
        },

        showView(id) {
          axios.get(`api/poll/${id}`)
            .then(response => this.pollView = response.data)
            .catch(err => this.errorMessage = 'Não foi possível carregar os dados.');

          this.toggleModalView();
        },

        showStats(id) {
            axios.get(`api/poll/${id}`)
                .then(response => this.pollStats.poll = response.data)
                .catch(err => this.errorMessage = 'Não foi possível carregar os dados.');

           axios.get(`api/poll/${id}/stats`)
                .then(response => this.pollStats.stats = response.data)
                .catch(err => this.errorMessage = 'Não foi possível carregar os dados.');

           this.toggleModalStats();
        },

        toggleModalForm() {
            this.isOpenModalForm = !this.isOpenModalForm;
        },

        toggleModalView() {
            this.isOpenModalView = !this.isOpenModalView;
        },

        toggleModalStats() {
            this.isOpenModalStats = !this.isOpenModalStats;
        },

        pollVote(option_id) {

            axios.post(`api/poll/${this.pollView.poll_id}/vote`, { option_id })
                .then(response => console.log(response))
                .catch(err => console.error(err));
            this.toggleModalView();
        },

        getOptions(id) {
            if (this.pollStats.poll) {
                const option = this.pollStats.poll.options.find(op => op.option_id === id);
                return option.option_description || '';
            }
            return null;
        }
    },
}
</script>

<style>

* {
    margin: 0;
    padding: 0;
    outline: 0;
    box-sizing: border-box;
}

html, body {
    background-color: #f2f2f2;
}

div#container {
    display: flex;
    flex-direction: column;
    padding: 100px 30px;
    justify-content: space-between;
    align-items: center;
}

#error {
    padding: 10px 20px;
    max-width: 700px;
    background-color: rgba(255, 0, 0, 0.10);
    border: 1px solid tomato;
    border-radius: 5px;
    color: red;
}

.poll-header {
    display: flex;
    margin: 50px;
    max-width: 700px;
    justify-content: space-around;
    align-items: center;
}

.new-poll {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: limegreen;
    color: white;
    font-weight: bold;
    margin-left: 20px;
    padding: 15px 30px;
    border-radius: 3px;
    cursor: pointer;
}

.poll {
    background-color: white;
    padding: 30px;
    max-width: 700px;
    border-radius: 5px;
    box-shadow: 0 2px 5px 1px rgba(0, 0, 0, 0.25);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.poll p {
    font-size: 1.2rem;
}

.poll .poll-options {
    margin-left: 15px;
    display: flex;
    flex-direction: column;
}

.poll-options button {
    width: 150px;
    height: 36px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    border-radius: 3px;
}

.poll-options button.view-poll {
    background-color: lightgray;
    margin-bottom: 10px;
}

.poll-options button.stats-poll {
    background-color: dodgerblue;
    color: white;
    margin-bottom: 10px;
}

.modal-container {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal {
    position: relative;
    background-color: white;
    display: flex;
    flex-direction: column;
    padding: 20px 30px;
    box-shadow: 0 2px 5px 1px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
    justify-content: space-between;
}

.modal h2 {
    width: 300px;;
}

.modal span {
    position: absolute;
    top: 30px;
    right: 30px;
    color: gray;
}

.modal form {
    display: flex;
    flex-direction: column;
}

.modal form label {
    margin-bottom: 20px;
}

.modal form input {
    width: 300px;
    height: 36px;
    display: block;
    border: 1px solid lightgray;
    border-radius: 3px;
    padding-left: 10px;
    margin: 5px 0;
}

.modal div.buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
}

div.buttons button {
    height: 36px;
    padding: 6px 20px;
    cursor: pointer;
    font-weight: bold;
    border-radius: 3px;
}

div.buttons button:last-child {
    background-color: limegreen;
    color: white;
}

.modal ul {
    width: 300px;
    list-style: none;
    margin-top: 30px;
}

.modal ul li {
    display: flex;
    justify-content: space-between;
}

.modal ul li + li {
    margin-top: 10px;
}

.modal button {
    padding: 6px 20px;
    margin-top: 10px;
    border-radius: 3px;
    font-weight: bold;
}

.modal ul li button {
    cursor: pointer;
    background-color: dodgerblue;
    color: white;
}
</style>
