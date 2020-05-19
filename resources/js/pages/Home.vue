<template>
    <div>
        <navbar></navbar>
        <h1 class="text-center my-3">Lista de Produtos</h1>

        <div class="container">
        <hr>


        <form>
            <div class="form-row">
                <div class="col-sm-3">
                    <label for="">Nome</label>
                    <input type="text" v-model="name" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="">Descrição</label>
                    <input type="text" v-model="description" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="">Preço</label>
                    <input type="text" v-model="price" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="">Quantidade</label>
                    <input type="text" v-model="amount"  class="form-control">
                </div>
            </div>

            <div class="mt-4 row justify-content-center">
                <button @click.prevent="createProduct" type="submit" class="btn btn-primary" v-if="!edit">
                    Cadastrar
                </button>

                <button @click.prevent="updateProduct(id)" class="btn btn-success" v-if="edit">
                    Atualizar
                </button>
            </div>
        </form>

        <hr>

        <div class="table-responsive">
            <table class="table table-hover text-center table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Opções</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(product, index) in products" :key="index">
                        <td>
                            {{ product.name }}
                        </td>

                        <td>
                            {{ product.description }}
                        </td>

                        <td>
                            R$ {{ product.price }}
                        </td>

                        <td>
                            {{ product.amount }}
                        </td>

                        <td>
                            <button @click.prevent="editProduct(product.id, index)" class="btn btn-success">
                                Editar
                            </button>
                            <button @click="deleteProduct(product.id)" class="btn btn-danger">
                                Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        </div>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            name: '',
            description: '',
            price: '',
            amount: '',
            products : [],
            edit: false,
            id: '',
            url: 'http://127.0.0.1:8000/api/product'
        };
    },
    methods: {
        createProduct() {
            axios.post(this.url, {
                'name' : this.name,
                'description' : this.description,
                'price' : this.price,
                'amount' : this.amount
            })
            .then(response => {
                Vue.swal({
                    title: 'Produto adicionado com sucesso!',
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true,
                    timerProgressBar: true,
                });

                this.name = '',
                this.description = '',
                this.price = '',
                this.amount = ''

                var newProducts = this.products;

                newProducts.push(response.data);

                this.products = newProducts;
            })
            .catch(error => {
                console.log(error);
            });
        },
        deleteProduct(id, index) {
            axios.delete(`${this.url}/${id}`)
            .then(response => {
                this.products.splice(index, 1);
                Vue.swal({
                    toast: true,
                    title : 'Produto Excluído com sucesso!',
                    icon: 'success',
                    position: 'top-end',
                    showCloseButton: true,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            })
            .catch(error => {
                console.log(error);
            });
        },
        editProduct(id) {
            this.edit = true;

            axios.get(`${this.url}/${id}`)
            .then(response => {
                this.name = response.data.name,
                this.description = response.data.description,
                this.price = response.data.price,
                this.amount = response.data.amount
                this.id = response.data.id;
            })
            .catch(error => {
                console.log(error)
            });
        },
        updateProduct(id) {
            axios.put(`${this.url}/${id}`, {
                'name' : this.name,
                'description' : this.description,
                'price' : this.price,
                'amount' : this.amount
            }).then(response => {
                this.name = '';
                this.description = '';
                this.price = '';
                this.amount = '';
                this.id = '';
                this.edit = false;

                this.getAllProducts();

                Vue.swal({
                    title: 'Produto editado com sucesso!',
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true,
                    timerProgressBar: true,
                });
            }).catch(error => {
                console.log(error);
            });
        },
        getAllProducts() {
            axios.get(this.url)
            .then(response => {
                this.products = response.data;
            })
            .catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getAllProducts();
    }
}
</script>

<style>

</style>
