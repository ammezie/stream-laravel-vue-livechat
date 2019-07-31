<template>
    <div id="chat">
        <button class="btn btn-primary c-chat-widget-button" ref="button" @click.prevent="toggleModal()">C</button>
        <div class="c-chat-widget" ref="modal" :class="{show: modal.show}">
            <div class="c-chat-widget-dialog">
                <div class="c-chat-widget-content">
                    <div class="c-chat-widget-header">Chat With Us</div>
                    <div class="c-chat-widget-body">
                        <div class="c-chat-widget-bubble c-chat-widget-bubble-left row" v-for="msg in messageData">
                            <div class="c-chat-widget-bubble-icon">{{msg.user.id}}</div>
                            <div class="c-chat-widget-bubble-text">
                               {{msg.text}}
                            </div>
                        </div>
                      <!--   <div class="c-chat-widget-bubble c-chat-widget-bubble-right">
                            <div class="c-chat-widget-bubble-icon">C</div>
                            <div class="c-chat-widget-bubble-text">
                                Dolores enim numquam fugit quas consequatur inventore, repellendus dignissimos!
                            </div>
                        </div> -->
                    </div>
                    <div class="c-chat-widget-footer">
                        <form @submit.prevent="sendMessage">
                            <textarea name="" id="" cols="30" v-model="message" rows="10" class="c-chat-widget-text" placeholder="Enter Text Here"></textarea>
                        <button class="btn btn-block btn-success">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  import { StreamChat } from 'stream-chat';
    export default {
        data() {
            return {
                modal: {
                    show: false,
                },
                message: '',
                messageData: [],
                collapsed: false,
                channel: null
            }
        },
        computed: {
        username() {
              return "admin"
           }
        },
        mounted() {
            this.initializeClient();
            this.createChannel();
        },
        methods: {
            async createChannel(){
            const {data} = await axios.post('/getChannel', {
                from_username: "admin",
                to_username: "client",
                from: "admin",
                to: "client",
            })
            const channel = this.client.channel('messaging', data.channel, {
                name: 'LiveChat channel',
                members: ["admin", "client"]
            });
            this.channel = channel
            channel.watch().then(state => {
                this.messages = state.messages
                channel.on('message.new', event => {
                    this.messageData.push(event.message)
                });
             })
            },
            async initializeClient () {

            const {data} = await axios.post('/generate-token', {
                name: "admin"
            })

            const client = new StreamChat(process.env.MIX_STREAM_API_KEY);
            await client.setUser(
                {
                    id: "admin",
                    name: "admin",
                },
                    data.token,
                );
                this.client = client
            },
            sendMessage() {
                this.channel && this.channel.sendMessage({
                    text: this.message
                });
                this.message = "";
            },
            toggleModal() {
                this.modal.show = !this.modal.show;
            },
            showModal() {
                this.modal.show = true;
            },
            hideModal() {
                this.modal.show = false;
            },

        }
    }
</script>
